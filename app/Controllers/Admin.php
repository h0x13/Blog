<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuditLogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Admin extends BaseController
{
    protected $userModel;
    protected $auditLogModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
        $this->auditLogModel = model(AuditLogModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $data['users'] = $this->userModel->findAll();
        return view('admin/users/index', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[user,admin]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ];

        if ($this->userModel->insert($data)) {
            // Log the user creation
            $this->auditLogModel->log(
                session()->get('user_id'),
                'CREATE',
                'USER',
                $this->userModel->getInsertID(),
                'Created new user: ' . $data['email']
            );

            return redirect()->to('/admin/users')
                ->with('message', 'User created successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to create user')
            ->with('message_type', 'danger');
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,user_id,$id]",
            'role' => 'required|in_list[user,admin]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role')
        ];

        // Handle password change if provided
        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            // Log the user update
            $this->auditLogModel->log(
                session()->get('user_id'),
                'UPDATE',
                'USER',
                $id,
                'Updated user: ' . $data['email']
            );

            return redirect()->to('/admin/users')
                ->with('message', 'User updated successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to update user')
            ->with('message_type', 'danger');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->userModel->delete($id)) {
            // Log the user deletion
            $this->auditLogModel->log(
                session()->get('user_id'),
                'DELETE',
                'USER',
                $id,
                'Deleted user: ' . $user['email']
            );

            return redirect()->to('/admin/users')
                ->with('message', 'User deleted successfully')
                ->with('message_type', 'success');
        }

        return redirect()->to('/admin/users')
            ->with('message', 'Failed to delete user')
            ->with('message_type', 'danger');
    }
} 