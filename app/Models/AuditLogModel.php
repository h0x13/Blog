<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'audit_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'action_type',
        'entity_type',
        'entity_id',
        'details'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $validationRules = [
        'user_id' => 'required|numeric',
        'action_type' => 'required|string',
        'entity_type' => 'required|string',
        'entity_id' => 'permit_empty|numeric',
        'details' => 'permit_empty|string'
    ];

    public function logUserAction($userId, $actionType, $entityType, $entityId = null, $details = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'action_type' => $actionType,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details
        ]);
    }
} 