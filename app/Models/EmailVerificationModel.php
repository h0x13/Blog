<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailVerificationModel extends Model
{
    protected $table = 'email_verifications';
    protected $primaryKey = 'verification_id';
    protected $allowedFields = ['user_id', 'token', 'expires_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function createVerification($userId)
    {
        $token = bin2hex(random_bytes(32));
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $now->modify('+5 minutes');
        $expiresAt = $now->format('Y-m-d H:i:s');

        $data = [
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => $expiresAt
        ];

        $this->insert($data);
        return $token;
    }

    public function verifyToken($token)
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $verification = $this->where('token', $token)
                            ->where('expires_at >', $now->format('Y-m-d H:i:s'))
                            ->first();

        if ($verification) {
            $this->delete($verification['verification_id']);
            return $verification['user_id'];
        }

        return false;
    }
} 