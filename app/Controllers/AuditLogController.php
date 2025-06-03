<?php

namespace App\Controllers;

use App\Models\AuditLogModel;

class AuditLogController extends BaseController
{
    protected $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $logs = $this->auditLogModel->where('user_id', $userId)
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll();

        return view('audit_logs', [
            'logs' => $logs
        ]);
    }
} 