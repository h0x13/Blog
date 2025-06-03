<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentReactionModel extends Model
{
    protected $table = 'comment_reactions';
    protected $primaryKey = 'reaction_id';
    protected $allowedFields = ['comment_id', 'user_id', 'reaction_type'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getReactionCounts($comment_id)
    {
        $likes = $this->where(['comment_id' => $comment_id, 'reaction_type' => 'like'])->countAllResults();
        $dislikes = $this->where(['comment_id' => $comment_id, 'reaction_type' => 'dislike'])->countAllResults();
        
        return [
            'likes' => $likes,
            'dislikes' => $dislikes
        ];
    }

    public function getUserReaction($comment_id, $user_id)
    {
        return $this->where(['comment_id' => $comment_id, 'user_id' => $user_id])->first();
    }
} 