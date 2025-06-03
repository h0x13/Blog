<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogReactionModel extends Model
{
    protected $table = 'blog_reactions';
    protected $primaryKey = 'reaction_id';
    protected $allowedFields = ['blog_id', 'user_id', 'reaction_type'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getReactionCounts($blog_id)
    {
        $likes = $this->where(['blog_id' => $blog_id, 'reaction_type' => 'like'])->countAllResults();
        $dislikes = $this->where(['blog_id' => $blog_id, 'reaction_type' => 'dislike'])->countAllResults();
        
        return [
            'likes' => $likes,
            'dislikes' => $dislikes
        ];
    }

    public function getUserReaction($blog_id, $user_id)
    {
        return $this->where(['blog_id' => $blog_id, 'user_id' => $user_id])->first();
    }
} 