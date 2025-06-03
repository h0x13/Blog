<?php

namespace App\Controllers;

use App\Models\AuditLogModel;
use App\Models\UserModel;

class AuditLogController extends BaseController
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
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $action = $this->request->getGet('action');
        $userId = session()->get('role') === 'admin' ? null : session()->get('id');

        // Get logs with pagination
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;

        $logs = $this->auditLogModel->getLogs($userId, $action, $perPage, $page);
        $total = $this->auditLogModel->getTotalLogs($userId, $action);

        // Create pager
        $pager = service('pager');
        $pager->makeLinks($page, $perPage, $total);

        // Get user names for admin view
        if (session()->get('role') === 'admin') {
            $userIds = array_column($logs, 'user_id');
            $users = $this->userModel->whereIn('user_id', $userIds)->findAll();
            $userMap = array_column($users, 'first_name', 'user_id');
            
            foreach ($logs as &$log) {
                $log['user_name'] = $userMap[$log['user_id']] ?? 'Unknown User';
            }
        }

        return view('audit_logs', [
            'logs' => $logs,
            'pager' => $pager
        ]);
    }

    public function export()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $action = $this->request->getGet('action');
        $userId = session()->get('role') === 'admin' ? null : session()->get('id');

        // Get all logs for export
        $logs = $this->auditLogModel->getLogs($userId, $action, null, null);

        // Create CSV content
        $csv = "Date & Time,Action,Entity Type,Details,IP Address";
        if (session()->get('role') === 'admin') {
            $csv .= ",User";
        }
        $csv .= "\n";

        foreach ($logs as $log) {
            $row = [
                date('Y-m-d H:i:s', strtotime($log['created_at'])),
                $log['action'],
                $log['entity_type'],
                $log['details'],
                $log['ip_address']
            ];

            if (session()->get('role') === 'admin') {
                $user = $this->userModel->find($log['user_id']);
                $row[] = $user ? $user['first_name'] . ' ' . $user['last_name'] : 'Unknown User';
            }

            $csv .= '"' . implode('","', array_map('addslashes', $row)) . "\"\n";
        }

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $csv;
        exit;
    }
} 