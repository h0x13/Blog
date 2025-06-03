<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\BlogModel;
use App\Models\CommentModel;
use App\Models\CommentReplyModel;

class Notification extends BaseController
{
    protected $notificationModel;
    protected $blogModel;
    protected $commentModel;
    protected $commentReplyModel;

    public function __construct()
    {
        $this->notificationModel = model(NotificationModel::class);
        $this->blogModel = model(BlogModel::class);
        $this->commentModel = model(CommentModel::class);
        $this->commentReplyModel = model(CommentReplyModel::class);
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $notifications = $this->notificationModel->getUserNotifications($userId);

        // Get additional data for each notification
        foreach ($notifications as &$notification) {
            switch ($notification['type']) {
                case 'comment':
                    $comment = $this->commentModel->find($notification['reference_id']);
                    if ($comment) {
                        $blog = $this->blogModel->find($comment['blog_id']);
                        $notification['blog_title'] = $blog['title'];
                        $notification['blog_slug'] = $blog['slug'];
                    }
                    break;
                case 'reply':
                    $reply = $this->commentReplyModel->find($notification['reference_id']);
                    if ($reply) {
                        $comment = $this->commentModel->find($reply['comment_id']);
                        $blog = $this->blogModel->find($comment['blog_id']);
                        $notification['blog_title'] = $blog['title'];
                        $notification['blog_slug'] = $blog['slug'];
                    }
                    break;
                case 'reaction':
                    $blog = $this->blogModel->find($notification['reference_id']);
                    if ($blog) {
                        $notification['blog_title'] = $blog['title'];
                        $notification['blog_slug'] = $blog['slug'];
                    }
                    break;
            }
        }

        $data = [
            'notifications' => $notifications,
            'unread_count' => $this->notificationModel->getUnreadCount($userId)
        ];

        return view('notifications', $data);
    }

    public function markAsRead($notificationId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $notification = $this->notificationModel->find($notificationId);

        if (!$notification || $notification['user_id'] !== $userId) {
            return $this->response->setJSON(['error' => 'Notification not found']);
        }

        $this->notificationModel->markAsRead($notificationId);
        return $this->response->setJSON(['success' => true]);
    }

    public function markAllAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $this->notificationModel->markAllAsRead($userId);
        return $this->response->setJSON(['success' => true]);
    }

    public function getUnreadCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $count = $this->notificationModel->getUnreadCount($userId);
        return $this->response->setJSON(['count' => $count]);
    }
} 