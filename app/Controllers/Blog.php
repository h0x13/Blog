<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BlogModel;
use App\Models\CategoryModel;
use App\Models\BlogCategoryModel;
use App\Models\UserModel;
use App\Models\CommentModel;
use App\Models\CommentReplyModel;
use App\Models\BlogReactionModel;
use App\Models\CommentReactionModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Blog extends BaseController
{
    protected $blogModel;
    protected $categoryModel;
    protected $blogCategoryModel;
    protected $validation;
    protected $userModel;
    protected $commentModel;
    protected $commentReplyModel;
    protected $blogReactionModel;
    protected $commentReactionModel;

    public function __construct()
    {
        $this->blogModel = model(BlogModel::class);
        $this->categoryModel = model(CategoryModel::class);
        $this->blogCategoryModel = model(BlogCategoryModel::class);
        $this->userModel = model(UserModel::class);
        $this->commentModel = model(CommentModel::class);
        $this->commentReplyModel = model(CommentReplyModel::class);
        $this->blogReactionModel = model(BlogReactionModel::class);
        $this->commentReactionModel = model(CommentReactionModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        helper('get_introduction');
        helper('url');

        $db = \Config\Database::connect();
        $builder = $db->table('blogs b');
        $builder->select('b.*, u.first_name, u.middle_name, u.last_name');
        $builder->join('users u', 'u.user_id = b.user_id');
        $builder->orderBy('b.created_at', 'DESC');

        // Get total count for pagination
        $total = $builder->countAllResults(false);
        
        // Set up pagination
        $perPage = 12;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;

        // Reset query and rebuild for paginated results
        $builder->resetQuery();
        $builder->select('b.*, u.first_name, u.middle_name, u.last_name');
        $builder->join('users u', 'u.user_id = b.user_id');
        $builder->orderBy('b.created_at', 'DESC');
        $builder->limit($perPage, $offset);
        $blogs = $builder->get()->getResultArray();

        // Create pager
        $pager = service('pager');
        $pager->setPath('blogs');
        $pager->makeLinks($perPage, $perPage, $total, 'default_full');

        // Get categories for the filter
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->findAll();

        return view('blogs', [
            'blogs' => $blogs,
            'categories' => $categories,
            'type' => 'recent',
            'pager' => $pager,
            'currentPage' => $currentPage
        ]);
    }

    public function popular()
    {
        helper('get_introduction');
        helper('url');

        $db = \Config\Database::connect();
        $builder = $db->table('blogs b');
        
        // Build the popularity score calculation
        $builder->select('
            b.*, 
            u.first_name, 
            u.middle_name, 
            u.last_name,
            COALESCE(COUNT(DISTINCT c.comment_id), 0) as comment_count,
            COALESCE(COUNT(DISTINCT br.reaction_id), 0) as reaction_count,
            (COALESCE(COUNT(DISTINCT c.comment_id), 0) + COALESCE(COUNT(DISTINCT br.reaction_id), 0)) as popularity_score
        ');
        $builder->join('users u', 'u.user_id = b.user_id');
        $builder->join('comments c', 'c.blog_id = b.blog_id', 'left');
        $builder->join('blog_reactions br', 'br.blog_id = b.blog_id', 'left');
        $builder->groupBy('b.blog_id, u.first_name, u.middle_name, u.last_name');
        $builder->orderBy('popularity_score', 'DESC');

        // Get total count for pagination
        $total = $builder->countAllResults(false);
        
        // Set up pagination
        $perPage = 12;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;

        // Reset query and rebuild for paginated results
        $builder->resetQuery();
        $builder->select('
            b.*, 
            u.first_name, 
            u.middle_name, 
            u.last_name,
            COALESCE(COUNT(DISTINCT c.comment_id), 0) as comment_count,
            COALESCE(COUNT(DISTINCT br.reaction_id), 0) as reaction_count,
            (COALESCE(COUNT(DISTINCT c.comment_id), 0) + COALESCE(COUNT(DISTINCT br.reaction_id), 0)) as popularity_score
        ');
        $builder->join('users u', 'u.user_id = b.user_id');
        $builder->join('comments c', 'c.blog_id = b.blog_id', 'left');
        $builder->join('blog_reactions br', 'br.blog_id = b.blog_id', 'left');
        $builder->groupBy('b.blog_id, u.first_name, u.middle_name, u.last_name');
        $builder->orderBy('popularity_score', 'DESC');
        $builder->limit($perPage, $offset);
        $blogs = $builder->get()->getResultArray();

        // Create pager
        $pager = service('pager');
        $pager->setPath('blogs/popular');
        $pager->makeLinks($perPage, $perPage, $total, 'default_full');

        // Get categories for the filter
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->findAll();

        return view('blogs', [
            'blogs' => $blogs,
            'categories' => $categories,
            'type' => 'popular',
            'pager' => $pager,
            'currentPage' => $currentPage
        ]);
    }

    public function manage()
    {
        helper('get_introduction');
        helper('url');

        $data = [
            'blogs' => $this->blogModel
                ->select('blogs.*, users.first_name, users.last_name, users.middle_name')
                ->join('users', 'users.user_id = blogs.user_id')
                ->where('blogs.user_id', session()->get('user_id'))
                ->findAll(),
            'validation' => $this->validation
        ];
        return view('manage_blogs', $data);
    }

    public function view($slug)
    {
        helper('reading');
        helper('url');

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

        // Get comments with user info
        $comments = $this->commentModel->getCommentsWithUser($blog['blog_id']);

        // Get reactions for each comment
        foreach ($comments as &$comment) {
            $comment['reactions'] = $this->commentReactionModel->getReactionCounts($comment['comment_id']);
            $comment['user_reaction'] = $this->commentReactionModel->getUserReaction($comment['comment_id'], session()->get('user_id'));
            $comment['replies'] = $this->commentReplyModel->getRepliesWithUser($comment['comment_id']);
        }

        // Get blog reactions
        $blog_reactions = $this->blogReactionModel->getReactionCounts($blog['blog_id']);
        $user_blog_reaction = $this->blogReactionModel->getUserReaction($blog['blog_id'], session()->get('user_id'));

        $data = [
            'title' => $blog['title'],
            'blog' => $blog,
            'author' => $author,
            'categories' => $categories,
            'comments' => $comments,
            'blog_reactions' => $blog_reactions,
            'user_blog_reaction' => $user_blog_reaction
        ];

        return view('blog_pages/view', $data);
    }

    public function create() {
        helper('url');

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

        $user_id = session()->get('user_id');

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
        helper('url');

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

        $user_id = session()->get('user_id');
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

    public function searchResult()
    {
        helper('get_introduction');
        helper('url');

        $query = $this->request->getGet('q');
        $data = [
            'blogs' => $this->blogModel
                ->select('blogs.*, users.first_name, users.last_name, users.middle_name')
                ->join('users', 'users.user_id = blogs.user_id')
                ->like('blogs.title', $query)
                ->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'search_query' => $query,
            'validation' => $this->validation,
            'type' => 'recent' // Default to recent blogs for search results
        ];
        return view('blogs', $data);
    }

    public function search()
    {
        $query = $this->request->getGet('query');
        
        if (empty($query)) {
            return $this->response->setJSON([]);
        }

        try {
            $blogs = $this->blogModel
                ->select('blogs.*, users.first_name, users.last_name, users.middle_name')
                ->join('users', 'users.user_id = blogs.user_id')
                ->like('blogs.title', $query)
                // ->orLike('blogs.content', $query)
                ->findAll();

            // Get categories for each blog
            foreach ($blogs as &$blog) {
                $blog['categories'] = $this->categoryModel
                    ->select('categories.*')
                    ->join('blog_categories', 'blog_categories.category_id = categories.category_id')
                    ->where('blog_categories.blog_id', $blog['blog_id'])
                    ->findAll();
            }

            log_message('debug', 'Search results: ' . json_encode($blogs));
            return $this->response->setJSON($blogs);
        } catch (\Exception $e) {
            log_message('error', 'Search error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'An error occurred while searching']);
        }
    }

    public function category($name)
    {
        helper('get_introduction');
        helper('url');

        $category = $this->categoryModel->where('name', $name)->first();
        
        if (!$category) {
            throw PageNotFoundException::forPageNotFound();
        }

        $blogs = $this->blogModel
            ->select('blogs.*, users.first_name, users.last_name, users.middle_name')
            ->join('users', 'users.user_id = blogs.user_id')
            ->join('blog_categories', 'blog_categories.blog_id = blogs.blog_id')
            ->where('blog_categories.category_id', $category['category_id'])
            ->findAll();

        $data = [
            'blogs' => $blogs,
            'categories' => $this->categoryModel->findAll(),
            'current_category' => $category,
            'validation' => $this->validation,
            'type' => 'recent' // Default to recent blogs for category view
        ];

        return view('blogs', $data);
    }

    public function addComment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'Please login to add comments']);
        }

        $rules = [
            'blog_id' => 'required|numeric',
            'content' => 'required|min_length[1]|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()]);
        }

        $blog_id = $this->request->getPost('blog_id');
        $user_id = session()->get('user_id');
        $content = trim($this->request->getPost('content'));

        // Verify blog exists
        if (!$this->blogModel->find($blog_id)) {
            return $this->response->setJSON(['error' => 'Blog not found']);
        }

        // Verify user exists
        if (!$this->userModel->find($user_id)) {
            return $this->response->setJSON(['error' => 'User not found']);
        }

        $data = [
            'blog_id' => $blog_id,
            'user_id' => $user_id,
            'content' => $content
        ];

        try {
            $comment_id = $this->commentModel->insert($data);
            if (!$comment_id) {
                return $this->response->setJSON(['error' => 'Failed to add comment']);
            }

            $comment = $this->commentModel->getCommentWithUser($comment_id);
            if (!$comment) {
                return $this->response->setJSON(['error' => 'Failed to retrieve comment data']);
            }

            return $this->response->setJSON([
                'success' => true, 
                'comment' => $comment,
                'message' => 'Comment added successfully',
                'csrf_token' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error adding comment: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred while adding the comment']);
        }
    }

    public function addReply()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'Please login to add replies']);
        }

        $rules = [
            'comment_id' => 'required|numeric',
            'content' => 'required|min_length[1]|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()]);
        }

        $comment_id = $this->request->getPost('comment_id');
        $user_id = session()->get('user_id');
        $content = trim($this->request->getPost('content'));

        // Verify comment exists
        if (!$this->commentModel->find($comment_id)) {
            return $this->response->setJSON(['error' => 'Comment not found']);
        }

        // Verify user exists
        if (!$this->userModel->find($user_id)) {
            return $this->response->setJSON(['error' => 'User not found']);
        }

        $data = [
            'comment_id' => $comment_id,
            'user_id' => $user_id,
            'content' => $content
        ];

        try {
            $reply_id = $this->commentReplyModel->insert($data);
            if (!$reply_id) {
                return $this->response->setJSON(['error' => 'Failed to add reply']);
            }

            $replies = $this->commentReplyModel->getRepliesWithUser($comment_id);
            if (!$replies) {
                return $this->response->setJSON(['error' => 'Failed to retrieve reply data']);
            }

            return $this->response->setJSON([
                'success' => true, 
                'reply' => $replies,
                'message' => 'Reply added successfully',
                'csrf_token' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error adding reply: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred while adding the reply']);
        }
    }

    public function reactToBlog()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'Please login to react to blogs']);
        }

        $rules = [
            'blog_id' => 'required|numeric',
            'reaction_type' => 'required|in_list[like,dislike]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()]);
        }

        $blog_id = $this->request->getPost('blog_id');
        $user_id = session()->get('user_id');
        $reaction_type = $this->request->getPost('reaction_type');

        // Verify blog exists
        if (!$this->blogModel->find($blog_id)) {
            return $this->response->setJSON(['error' => 'Blog not found']);
        }

        // Verify user exists
        if (!$this->userModel->find($user_id)) {
            return $this->response->setJSON(['error' => 'User not found']);
        }

        try {
            $existing_reaction = $this->blogReactionModel->getUserReaction($blog_id, $user_id);

            if ($existing_reaction) {
                if ($existing_reaction['reaction_type'] === $reaction_type) {
                    // Remove reaction if clicking the same button
                    $this->blogReactionModel->delete($existing_reaction['reaction_id']);
                    $user_reaction = null;
                } else {
                    // Update reaction if changing from like to dislike or vice versa
                    $this->blogReactionModel->update($existing_reaction['reaction_id'], ['reaction_type' => $reaction_type]);
                    $user_reaction = ['reaction_type' => $reaction_type];
                }
            } else {
                // Add new reaction
                $this->blogReactionModel->insert([
                    'blog_id' => $blog_id,
                    'user_id' => $user_id,
                    'reaction_type' => $reaction_type
                ]);
                $user_reaction = ['reaction_type' => $reaction_type];
            }

            $reactions = $this->blogReactionModel->getReactionCounts($blog_id);

            return $this->response->setJSON([
                'success' => true,
                'reactions' => $reactions,
                'user_reaction' => $user_reaction,
                'message' => 'Reaction updated successfully',
                'csrf_token' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating blog reaction: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred while updating the reaction']);
        }
    }

    public function reactToComment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['error' => 'Please login to react to comments']);
        }

        $rules = [
            'comment_id' => 'required|numeric',
            'reaction_type' => 'required|in_list[like,dislike]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()]);
        }

        $comment_id = $this->request->getPost('comment_id');
        $user_id = session()->get('user_id');
        $reaction_type = $this->request->getPost('reaction_type');

        // Verify comment exists
        if (!$this->commentModel->find($comment_id)) {
            return $this->response->setJSON(['error' => 'Comment not found']);
        }

        // Verify user exists
        if (!$this->userModel->find($user_id)) {
            return $this->response->setJSON(['error' => 'User not found']);
        }

        try {
            $existing_reaction = $this->commentReactionModel->getUserReaction($comment_id, $user_id);

            if ($existing_reaction) {
                if ($existing_reaction['reaction_type'] === $reaction_type) {
                    // Remove reaction if clicking the same button
                    $this->commentReactionModel->delete($existing_reaction['reaction_id']);
                    $user_reaction = null;
                } else {
                    // Update reaction if changing from like to dislike or vice versa
                    $this->commentReactionModel->update($existing_reaction['reaction_id'], ['reaction_type' => $reaction_type]);
                    $user_reaction = ['reaction_type' => $reaction_type];
                }
            } else {
                // Add new reaction
                $this->commentReactionModel->insert([
                    'comment_id' => $comment_id,
                    'user_id' => $user_id,
                    'reaction_type' => $reaction_type
                ]);
                $user_reaction = ['reaction_type' => $reaction_type];
            }

            $reactions = $this->commentReactionModel->getReactionCounts($comment_id);

            return $this->response->setJSON([
                'success' => true,
                'reactions' => $reactions,
                'user_reaction' => $user_reaction,
                'message' => 'Reaction updated successfully',
                'csrf_token' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating comment reaction: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred while updating the reaction']);
        }
    }
}
