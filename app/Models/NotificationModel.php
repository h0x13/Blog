<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'type',
        'reference_id',
        'message',
        'is_read',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createNotification($userId, $type, $referenceId, $message)
    {
        return $this->insert([
            'user_id' => $userId,
            'type' => $type,
            'reference_id' => $referenceId,
            'message' => $message,
            'is_read' => false
        ]);
    }

    public function getUserNotifications($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, ['is_read' => true]);
    }

    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', false)
                    ->set(['is_read' => true])
                    ->update();
    }

    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', false)
                    ->countAllResults();
    }
} 