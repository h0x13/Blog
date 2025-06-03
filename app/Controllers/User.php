<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AuditLogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class User extends BaseController
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
        $data = [
            'users' => $this->userModel->findAll(),
            'validation' => $this->validation
        ];
        return view('users', $data);
    }

    public function add()
    {
        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[admin,author,viewer]',
            'is_enabled' => 'required|in_list[0,1]',
            'gender' => 'permit_empty|in_list[Male,Female,Other]',
            'birthdate' => 'permit_empty|valid_date',
            'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = null;

        if ($image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/users', $imageName);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'is_enabled' => $this->request->getPost('is_enabled'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate'),
            'image' => $imageName
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/users')
                ->with('message', 'User added successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to add user')
            ->with('message_type', 'danger');
    }

    public function edit($id)
    {
        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,user_id,{$id}]",
            'password' => 'permit_empty|min_length[8]',
            'role' => 'required|in_list[admin,author,viewer]',
            'is_enabled' => 'required|in_list[0,1]',
            'gender' => 'permit_empty|in_list[Male,Female,Other]',
            'birthdate' => 'permit_empty|valid_date',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'is_enabled' => $this->request->getPost('is_enabled'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image->isValid() && !$image->hasMoved()) {
            // Delete old image if not default
            if ($user['image'] !== null) {
                unlink(WRITEPATH . 'uploads/users/' . $user['image']);
            }
            
            $imageName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/users', $imageName);
            $data['image'] = $imageName;
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/users')
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
        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Delete user image if not default
        if ($user['image'] !== null) {
            unlink(WRITEPATH . 'uploads/users/' . $user['image']);
        }

        $message = 'Failed to delete user';
        $messageType = 'danger';

        if ($this->userModel->delete($id)) {
            $message = 'User deleted successfully';
            $messageType = 'success';
        }

        return redirect()->back()
            ->with('message', $message)
            ->with('message_type', $messageType);
    }
    
    public function profile() 
    {
        $user = $this->userModel->find(session()->get('user_id'));
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Get user's audit logs
        $logs = $this->auditLogModel->getUserLogs($user['user_id']);
        
        return view('profile', [
            'user' => $user, 
            'validation' => $this->validation,
            'logs' => $logs
        ]);
    }

    public function updateProfile()
    {
        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,user_id," . session()->get('user_id') . "]",
            'current_password' => 'permit_empty|min_length[8]',
            'new_password' => 'permit_empty|min_length[8]',
            'gender' => 'permit_empty|in_list[Male,Female,Other]',
            'birthdate' => 'permit_empty|valid_date',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate')
        ];

        // Handle password change if provided
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        if ($currentPassword && $newPassword) {
            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('message', 'Current password is incorrect')
                    ->with('message_type', 'danger');
            }
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image->isValid() && !$image->hasMoved()) {
            // Delete old image if exists
            if ($user['image'] !== null) {
                $oldImagePath = WRITEPATH . 'uploads/profile/' . $user['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $imageName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/profile', $imageName);
            $data['image'] = $imageName;
        }

        // Handle image removal
        if ($this->request->getPost('remove_image')) {
            if ($user['image'] !== null) {
                $oldImagePath = WRITEPATH . 'uploads/profile/' . $user['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $data['image'] = null;
        }

        if ($this->userModel->update($userId, $data)) {
            // Log the profile update
            $this->auditLogModel->log(
                $userId,
                'UPDATE',
                'PROFILE',
                $userId,
                'User updated their profile'
            );

            return redirect()->to('/profile')
                ->with('message', 'Profile updated successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to update profile')
            ->with('message_type', 'danger');
    }

    public function auditLogs()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $logs = $this->auditLogModel->getAllLogs();
        return view('audit_logs', ['logs' => $logs]);
    }
}
