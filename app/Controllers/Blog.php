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
use App\Models\NotificationModel;
use App\Models\AuditLogModel;
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
    protected $notificationModel;
    protected $auditLogModel;

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
        $this->notificationModel = model(NotificationModel::class);
        $this->auditLogModel = model(AuditLogModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        helper('get_introduction');
        helper('url');

        $perPage = 12; // Number of blogs per page
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $totalBlogs = $this->blogModel->countAllResults();
        $totalPages = ceil($totalBlogs / $perPage);

        $data = [
            'blogs' => $this->blogModel
                ->select('blogs.*, users.first_name, users.last_name, users.middle_name')
                ->join('users', 'users.user_id = blogs.user_id')
                ->orderBy('blogs.created_at', 'DESC')
                ->limit($perPage, $offset)
                ->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'validation' => $this->validation,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1
        ];
        return view('blogs', $data);
    }

    public function popular()
    {
        helper('get_introduction');
        helper('url');

        $perPage = 12; // Number of blogs per page
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        // Get total count of blogs
        $totalBlogs = $this->blogModel->countAllResults();
        $totalPages = ceil($totalBlogs / $perPage);

        // Get blogs with their reaction counts
        $blogs = $this->blogModel
            ->select('blogs.*, users.first_name, users.last_name, users.middle_name, 
                     (SELECT COUNT(*) FROM blog_reactions WHERE blog_reactions.blog_id = blogs.blog_id AND blog_reactions.reaction_type = "like") as like_count,
                     (SELECT COUNT(*) FROM blog_reactions WHERE blog_reactions.blog_id = blogs.blog_id AND blog_reactions.reaction_type = "dislike") as dislike_count')
            ->join('users', 'users.user_id = blogs.user_id')
            ->orderBy('like_count', 'DESC')
            ->orderBy('blogs.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        $data = [
            'blogs' => $blogs,
            'categories' => $this->categoryModel->findAll(),
            'validation' => $this->validation,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1
        ];
        return view('blogs', $data);
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

    public function create()
    {
        if (!$this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]',
            'category_id' => 'required|integer',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category_id' => $this->request->getPost('category_id'),
            'user_id' => session()->get('user_id')
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/blogs', $imageName);
            $data['image'] = $imageName;
        }

        if ($this->blogModel->insert($data)) {
            // Log the blog creation
            $this->auditLogModel->log(
                session()->get('user_id'),
                'CREATE',
                'BLOG',
                $this->blogModel->getInsertID(),
                'Created new blog post: ' . $data['title']
            );

            return redirect()->to('/blogs')
                ->with('message', 'Blog post created successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to create blog post')
            ->with('message_type', 'danger');
    }

    public function update($id)
    {
        $blog = $this->blogModel->find($id);
        if (!$blog || $blog['user_id'] !== session()->get('user_id')) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate([
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]',
            'category_id' => 'required|integer',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category_id' => $this->request->getPost('category_id')
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image->isValid() && !$image->hasMoved()) {
            // Delete old image if exists
            if ($blog['image'] !== null) {
                $oldImagePath = WRITEPATH . 'uploads/blogs/' . $blog['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $imageName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/blogs', $imageName);
            $data['image'] = $imageName;
        }

        if ($this->blogModel->update($id, $data)) {
            // Log the blog update
            $this->auditLogModel->log(
                session()->get('user_id'),
                'UPDATE',
                'BLOG',
                $id,
                'Updated blog post: ' . $data['title']
            );

            return redirect()->to('/blogs')
                ->with('message', 'Blog post updated successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to update blog post')
            ->with('message_type', 'danger');
    }

    public function delete($id)
    {
        $blog = $this->blogModel->find($id);
        if (!$blog || $blog['user_id'] !== session()->get('user_id')) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Delete image if exists
        if ($blog['image'] !== null) {
            $imagePath = WRITEPATH . 'uploads/blogs/' . $blog['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->blogModel->delete($id)) {
            // Log the blog deletion
            $this->auditLogModel->log(
                session()->get('user_id'),
                'DELETE',
                'BLOG',
                $id,
                'Deleted blog post: ' . $blog['title']
            );

            return redirect()->to('/blogs')
                ->with('message', 'Blog post deleted successfully')
                ->with('message_type', 'success');
        }

        return redirect()->to('/blogs')
            ->with('message', 'Failed to delete blog post')
            ->with('message_type', 'danger');
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
            'validation' => $this->validation
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
            'validation' => $this->validation
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
        $blog = $this->blogModel->find($blog_id);
        if (!$blog) {
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

            // Create notification for blog owner
            if ($blog['user_id'] !== $user_id) {
                $this->notificationModel->createNotification(
                    $blog['user_id'],
                    'comment',
                    $comment_id,
                    sprintf(
                        '%s commented on your blog "%s"',
                        session()->get('user_name'),
                        $blog['title']
                    )
                );
            }

            // Log the comment
            $this->auditLogModel->log(
                session()->get('user_id'),
                'COMMENT',
                'BLOG',
                $blog_id,
                'Added a comment to blog post'
            );

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
        $comment = $this->commentModel->find($comment_id);
        if (!$comment) {
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

            // Create notification for comment owner
            if ($comment['user_id'] !== $user_id) {
                $blog = $this->blogModel->find($comment['blog_id']);
                $this->notificationModel->createNotification(
                    $comment['user_id'],
                    'reply',
                    $reply_id,
                    sprintf(
                        '%s replied to your comment on "%s"',
                        session()->get('user_name'),
                        $blog['title']
                    )
                );
            }

            // Log the reply
            $this->auditLogModel->log(
                session()->get('user_id'),
                'REPLY',
                'BLOG',
                $comment['blog_id'],
                'Added a reply to blog post'
            );

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
        $blog = $this->blogModel->find($blog_id);
        if (!$blog) {
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

                // Create notification for blog owner
                if ($blog['user_id'] !== $user_id) {
                    $this->notificationModel->createNotification(
                        $blog['user_id'],
                        'reaction',
                        $blog_id,
                        sprintf(
                            '%s %s your blog "%s"',
                            session()->get('user_name'),
                            $reaction_type === 'like' ? 'liked' : 'disliked',
                            $blog['title']
                        )
                    );
                }
            }

            $reactions = $this->blogReactionModel->getReactionCounts($blog_id);

            // Log the reaction
            $this->auditLogModel->log(
                session()->get('user_id'),
                'REACTION',
                'BLOG',
                $blog_id,
                sprintf(
                    '%s %s blog "%s"',
                    session()->get('user_name'),
                    $reaction_type === 'like' ? 'liked' : 'disliked',
                    $blog['title']
                )
            );

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

            // Log the reaction
            $this->auditLogModel->log(
                session()->get('user_id'),
                'REACTION',
                'BLOG',
                $comment['blog_id'],
                sprintf(
                    '%s %s comment on blog "%s"',
                    session()->get('user_name'),
                    $reaction_type === 'like' ? 'liked' : 'disliked',
                    $comment['content']
                )
            );

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

    public function commentReaction($comment_id, $reaction_type)
    {
        if (!in_array($reaction_type, ['like', 'dislike'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid reaction type'
            ]);
        }

        $comment = $this->commentModel->find($comment_id);
        if (!$comment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Comment not found'
            ]);
        }

        $user_id = session()->get('user_id');
        $existing_reaction = $this->commentReactionModel->where([
            'comment_id' => $comment_id,
            'user_id' => $user_id
        ])->first();

        if ($existing_reaction) {
            if ($existing_reaction['reaction_type'] === $reaction_type) {
                $this->commentReactionModel->delete($existing_reaction['reaction_id']);
            } else {
                $this->commentReactionModel->update($existing_reaction['reaction_id'], [
                    'reaction_type' => $reaction_type
                ]);
            }
        } else {
            $this->commentReactionModel->insert([
                'comment_id' => $comment_id,
                'user_id' => $user_id,
                'reaction_type' => $reaction_type
            ]);
        }

        $reactions = $this->commentReactionModel->getReactionCounts($comment_id);

        // Log the reaction
        $this->auditLogModel->log(
            session()->get('user_id'),
            'REACTION',
            'BLOG',
            $comment['blog_id'],
            sprintf(
                '%s %s comment on blog "%s"',
                session()->get('user_name'),
                $reaction_type === 'like' ? 'liked' : 'disliked',
                $comment['content']
            )
        );

        return $this->response->setJSON([
            'success' => true,
            'reactions' => $reactions
        ]);
    }
}
