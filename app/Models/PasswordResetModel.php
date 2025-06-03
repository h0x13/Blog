<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'reset_id';
    protected $allowedFields = ['user_id', 'token', 'expires_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function createReset($userId)
    {
        $token = bin2hex(random_bytes(32));
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $now->modify('+1 hour');
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
        $reset = $this->where('token', $token)
                      ->where('expires_at >', $now->format('Y-m-d H:i:s'))
                      ->first();

        if ($reset) {
            $this->delete($reset['reset_id']);
            return $reset['user_id'];
        }

        return false;
    }
} 