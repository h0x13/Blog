<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentReplyModel extends Model
{
    protected $table = 'comment_replies';
    protected $primaryKey = 'reply_id';
    protected $allowedFields = ['comment_id', 'user_id', 'content'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getRepliesWithUser($comment_id)
    {
        return $this->select('comment_replies.*, users.first_name, users.last_name, users.middle_name, users.image')
            ->join('users', 'users.user_id = comment_replies.user_id')
            ->where('comment_replies.comment_id', $comment_id)
            ->orderBy('comment_replies.created_at', 'ASC')
            ->findAll();
    }
} 