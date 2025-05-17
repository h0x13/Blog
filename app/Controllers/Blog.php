<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BlogModel;
use App\Models\CategoryModel;
use App\Models\BlogCategoryModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Blog extends BaseController
{
    protected $blogModel;
    protected $categoryModel;
    protected $blogCategoryModel;
    protected $validation;
    protected $userModel;

    public function __construct()
    {
        $this->blogModel = model(BlogModel::class);
        $this->categoryModel = model(CategoryModel::class);
        $this->blogCategoryModel = model(BlogCategoryModel::class);
        $this->userModel = model(UserModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        helper('get_introduction');

        $data = [
            'blogs' => $this->blogModel
                ->select('blogs.*, users.first_name, users.last_name, users.middle_name')
                ->join('users', 'users.user_id = blogs.user_id')
                ->findAll(),
            'validation' => $this->validation
        ];
        return view('blogs', $data);
    }

    public function view($slug)
    {
        helper('reading');
        $blog = $this->blogModel->where('slug', $slug)->first();
        
        if (!$blog) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Get author info
        $author = $this->userModel->find($blog['user_id']);
        
        // Get categories for this blog
        $categories = $this->categoryModel->select('categories.*')
            ->join('blog_categories', 'blog_categories.category_id = categories.category_id')
            ->where('blog_categories.blog_id', $blog['blog_id'])
            ->findAll();

        $data = [
            'title' => $blog['title'],
            'blog' => $blog,
            'author' => $author,
            'categories' => $categories,
        ];

        return view('blog_pages/view', $data);
    }

    public function create() {
        $data['categories'] = $this->categoryModel->findAll();
        return view('blog_pages/add.php', $data);
    } 

    public function store() {
        helper('blog_title_slugify');

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'content' => 'required',
            'visibility' => 'required|in_list[private,public]',
            'thumbnail' => 'permit_empty|uploaded[thumbnail]|max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // $user_id = session()->get('user_id');
        $user_id = 2;

        $thumbnail = $this->request->getFile('thumbnail');
        $thumbnailName = null;

        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $thumbnailName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/thumbnails', $thumbnailName);
        }
        
        $data = [
            'user_id' => $user_id,
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'visibility' => $this->request->getPost('visibility'),
            'slug' => blog_title_slugify($this->request->getPost('title')),
            'thumbnail' => $thumbnailName,
        ];

        $this->blogModel->db->transBegin();
        try {
            $blog_id = $this->blogModel->insert($data);
            if (!$blog_id) {
                return redirect()->back()->withInput()->with('errors', $this->blogModel->errors());
            }
            
            // Save categories if selected
            $categories = $this->request->getPost('categories');
            if (!empty($categories)) {
                foreach ($categories as $category_id) {
                    $this->blogCategoryModel->insert([
                        'blog_id' => $blog_id,
                        'category_id' => $category_id
                    ]);
                }
            }
            
            $this->blogModel->db->transCommit();
            return redirect()->to('blogs')->with('success', 'Blog created successfully!');
            
        } catch (\Exception $e) {
            $this->blogModel->db->transRollback();
            return redirect()->back()->withInput()->with('errors', ["An error occurred while creating the blog: {$e->getMessage()}"]);
        }
    }

    public function update($slug) {
        $categories = $this->categoryModel->findAll();
        $blog = $this->blogModel->where('slug', $slug)->first();

        if (!$blog) {
            throw PageNotFoundException::forPageNotFound();
        }

        $blog_categories = $this->blogCategoryModel->select('category_id')->where('blog_id', $blog['blog_id'])->findColumn('category_id');
        $blog['categories'] = $blog_categories;

        $data = ['categories' => $categories, 'blog' => $blog];
        return view('blog_pages/edit.php', $data);
    }
    
    public function save($slug) {
        helper('blog_title_slugify');

        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'content' => 'required',
            'visibility' => 'required|in_list[private,public]',
            'thumbnail' => 'permit_empty|is_image[thumbnail]|max_size[thumbnail,2048]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // $user_id = session()->get('user_id');
        $user_id = 2;
        $new_slug = blog_title_slugify($this->request->getPost('title'));
        
        $this->blogModel->db->transBegin();
        try {
            $blog = $this->blogModel->where('slug', $slug)->first();
            if (!$blog) {
                throw PageNotFoundException::forPageNotFound();
            }

            $data = [
                'user_id' => $user_id,
                'title' => $this->request->getPost('title'),
                'content' => $this->request->getPost('content'),
                'visibility' => $this->request->getPost('visibility'),
                'slug' => $new_slug
            ];

            $thumbnail = $this->request->getFile('thumbnail');
            $old_thumbnail = $this->request->getPost('old_thumbnail');


            if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
                // Upload new thumbnail
                $thumbnailName = $thumbnail->getRandomName();
                $thumbnail->move(WRITEPATH . 'uploads/thumbnails', $thumbnailName);
                $data['thumbnail'] = $thumbnailName;
                
                // Delete old thumbnail if exists
                if (!empty($blog['thumbnail']) && file_exists(WRITEPATH . 'uploads/thumbnails/' . $blog['thumbnail'])) {
                    unlink(WRITEPATH . 'uploads/blogs/' . $blog['thumbnail']);
                }
            } else if (empty($old_thumbnail)) {
                // If old_thumbnail is empty and no new file was uploaded, set thumbnail to null
                $data['thumbnail'] = null;
                
                // Delete old thumbnail if exists
                if (!empty($blog['thumbnail']) && file_exists(WRITEPATH . 'uploads/thumbnails/' . $blog['thumbnail'])) {
                    unlink(WRITEPATH . 'uploads/thumbnails/' . $blog['thumbnail']);
                }
            }
            log_message('error', json_encode($data));


            $blog_id = $blog['blog_id'];
            $this->blogModel->update($blog_id, $data);


            $this->blogCategoryModel->where('blog_id', $blog_id)->delete();
            
            $categories = $this->request->getPost('categories');
            if (!empty($categories)) {
                foreach ($categories as $category_id) {
                    $this->blogCategoryModel->insert([
                        'blog_id' => $blog_id,
                        'category_id' => $category_id
                    ]);
                }
            }
            
            $this->blogModel->db->transCommit();
            return redirect()->to("blogs/edit/$new_slug")->with('success', 'Blog created successfully!');
            
        } catch (\Exception $e) {
            $this->blogModel->db->transRollback();
            return redirect()->back()->withInput()->with('errors', ["An error occurred while creating the blog: {$e->getMessage()}"]);
        }
    }

    public function delete($slug) {
        $blog = $this->blogModel->where('slug', $slug)->first();
        
        if (!$blog) {
            throw PageNotFoundException::forPageNotFound();
        }

        $user = $this->userModel->find($blog['user_id']);

        if ($this->request->getPost('slug_confirmation') !== $blog['slug']) {
            return redirect()->back()->withInput()->with('errors', 'Delete Confirmation Failed!');
        }

        // Delete the thumbnail if it exists
        if (!empty($blog['thumbnail'])) {
            $thumbnailPath = WRITEPATH . 'uploads/thumbnails/' . $blog['thumbnail'];
            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }
        }

        $this->blogModel->delete($blog['blog_id']);
        return redirect()->to("blogs")->with('success', "<b>{$blog['title']}</b> by <b>{$user['first_name']} {$user['last_name']}</b> deleted successfully!");
    }


    function thumbnail($filename)
    {
        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $mimeType = mime_content_type($path);
        return $this->response->setHeader('Content-Type', $mimeType)->setBody(file_get_contents($path));
    }


    function image($filename)
    {
        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'blogs' . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $mimeType = mime_content_type($path);
        return $this->response->setHeader('Content-Type', $mimeType)->setBody(file_get_contents($path));
    }


    public function upload_image()
    {
        if (!$this->validate([
            'file' => 'uploaded[file]|is_image[file]|max_size[file,4096]|mime_in[file,image/jpg,image/jpeg,image/gif,image/png]'
        ])) {
            return $this->response->setJSON(['error' => 'Invalid file']);
        }

        // Move the file to a public folder
        $file = $this->request->getFile('file');
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/blogs', $newName);

        // Return the URL to the uploaded image
        return $this->response->setJSON([
            'url' => base_url('blogs/image/' . $newName),
        ]);
    }


    public function delete_image()
    {
        $imageUrl = $this->request->getPost('image_url');
        
        // Extract the file name from the URL
        $fileName = basename($imageUrl);

        $filePath = WRITEPATH . 'uploads/blogs' . $fileName;

        // Check if the file exists and delete it
        if (file_exists($filePath)) {
            unlink($filePath);
            return $this->response->setJSON(['success' => 'Image deleted successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Image not found']);
        }
    }
}
