<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $allowedFields = ['blog_id', 'user_id', 'content'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCommentsWithUser($blog_id)
    {
        return $this->select('comments.*, users.first_name, users.last_name, users.middle_name, users.image')
            ->join('users', 'users.user_id = comments.user_id')
            ->where('comments.blog_id', $blog_id)
            ->orderBy('comments.created_at', 'DESC')
            ->findAll();
    }

    public function getCommentWithUser($comment_id)
    {
        return $this->select('comments.*, users.first_name, users.last_name, users.middle_name, users.image')
            ->join('users', 'users.user_id = comments.user_id')
            ->where('comments.comment_id', $comment_id)
            ->first();
    }
} 