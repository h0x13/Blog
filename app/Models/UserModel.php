<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\EmailVerificationModel;
use App\Libraries\EmailService;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['first_name', 'last_name', 
        'middle_name', 'email', 'password', 'role', 'is_enabled', 
        'image', 'birthdate', 'gender', 'theme', 'reset_token', 'reset_expires'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            log_message('debug', 'UserModel::hashPassword - No password in data');
            return $data;
        }

        log_message('debug', 'UserModel::hashPassword - Hashing password');
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        log_message('debug', 'UserModel::hashPassword - New hash: ' . $data['data']['password']);
        return $data;
    }

    public function register($data)
    {
        try {
            // Set default values
            $data['is_enabled'] = false; // User needs to verify email first
            $data['role'] = 'user';

            // Insert the user
            $userId = $this->insert($data);

            if ($userId) {
                // Create email verification
                $emailVerificationModel = new EmailVerificationModel();
                $token = $emailVerificationModel->createVerification($userId);

                // Send verification email
                $emailService = new EmailService();
                $emailService->sendVerificationEmail($data['email'], $token);

                return $userId;
            }
        } catch (\Exception $e) {
            log_message('error', '[UserModel::register] Error: ' . $e->getMessage());
            return false;
        }

        return false;
    }
}
