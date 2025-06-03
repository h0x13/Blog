<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('id');
        $notifications = $this->notificationModel->where('user_id', $userId)
                                                ->orderBy('created_at', 'DESC')
                                                ->findAll();

        return view('notifications', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authorized']);
        }

        $userId = session()->get('id');
        $notification = $this->notificationModel->where('notification_id', $id)
                                              ->where('user_id', $userId)
                                              ->first();

        if ($notification) {
            $this->notificationModel->update($id, ['is_read' => true]);
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Notification not found']);
    }

    public function markAllAsRead()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authorized']);
        }

        $userId = session()->get('id');
        $this->notificationModel->where('user_id', $userId)
                              ->set(['is_read' => true])
                              ->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function getUnreadCount()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['count' => 0]);
        }

        $userId = session()->get('id');
        $count = $this->notificationModel->where('user_id', $userId)
                                       ->where('is_read', false)
                                       ->countAllResults();

        return $this->response->setJSON(['count' => $count]);
    }
} 