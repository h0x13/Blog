<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';
    protected $allowedFields = [
        'user_id', 'action', 'entity_type', 'entity_id', 
        'details', 'ip_address', 'user_agent'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function log($userId, $action, $entityType, $entityId = null, $details = null)
    {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString()
        ];

        return $this->insert($data);
    }

    public function getUserLogs($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    public function getAllLogs($limit = 50)
    {
        return $this->select('audit_logs.*, users.first_name, users.last_name, users.middle_name')
                    ->join('users', 'users.user_id = audit_logs.user_id')
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    public function getLogs($userId = null, $action = null, $perPage = null, $page = null)
    {
        $builder = $this->builder();
        
        // Apply filters
        if ($userId !== null) {
            $builder->where('user_id', $userId);
        }
        
        if ($action !== null && $action !== 'all') {
            $builder->where('action', $action);
        }

        // Order by most recent first
        $builder->orderBy('created_at', 'DESC');

        // Apply pagination if specified
        if ($perPage !== null && $page !== null) {
            $offset = ($page - 1) * $perPage;
            $builder->limit($perPage, $offset);
        }

        return $builder->get()->getResultArray();
    }

    public function getTotalLogs($userId = null, $action = null)
    {
        $builder = $this->builder();
        
        // Apply filters
        if ($userId !== null) {
            $builder->where('user_id', $userId);
        }
        
        if ($action !== null && $action !== 'all') {
            $builder->where('action', $action);
        }

        return $builder->countAllResults();
    }
} 