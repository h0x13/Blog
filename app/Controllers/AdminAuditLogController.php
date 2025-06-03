<?php

namespace App\Controllers;

use App\Models\AuditLogModel;
use App\Models\UserModel;

class AdminAuditLogController extends BaseController
{
    protected $auditLogModel;
    protected $userModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Check if user is logged in and is admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find(session()->get('user_id'));
        if (!$user || $user['role'] !== 'admin') {
            return redirect()->to('/')
                ->with('error', 'You do not have permission to access this page.');
        }

        // Get all audit logs with user information
        $logs = $this->auditLogModel->select('audit_logs.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.user_id = audit_logs.user_id')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->paginate(10);

        $data = [
            'logs' => $logs,
            'pager' => $this->auditLogModel->pager,
            'title' => 'Admin Audit Log'
        ];

        return view('admin/audit_logs', $data);
    }
} 