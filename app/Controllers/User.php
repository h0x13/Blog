<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class User extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
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
        // Prevent editing current admin account
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')
                ->with('message', 'You cannot edit your own account from here. Please use the profile page instead.')
                ->with('message_type', 'warning');
        }

        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,user_id,{$id}]",
            'password' => 'permit_empty|min_length[8]',
            'role' => 'required|in_list[admin,author,viewer]',
            'is_enabled' => 'required|in_list[0,1]',
            'gender' => 'permit_empty|in_list[male,female,other]',
            'birthdate' => 'permit_empty|valid_date',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validation)
                ->with('message', 'Please check the form for errors')
                ->with('message_type', 'danger');
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
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/users';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old image if exists
            if (!empty($user['image']) && file_exists($uploadPath . '/' . $user['image'])) {
                unlink($uploadPath . '/' . $user['image']);
            }
            
            $imageName = $image->getRandomName();
            $image->move($uploadPath, $imageName);
            $data['image'] = $imageName;
        }

        // Handle image removal
        if ($this->request->getPost('remove_image') === '1') {
            $uploadPath = WRITEPATH . 'uploads/users';
            if (!empty($user['image']) && file_exists($uploadPath . '/' . $user['image'])) {
                unlink($uploadPath . '/' . $user['image']);
            }
            $data['image'] = null;
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/users')
                ->with('message', 'User updated successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('validation', $this->validation)
            ->with('message', 'Failed to update user')
            ->with('message_type', 'danger');
    }

    public function delete($id)
    {
        // Prevent deleting current admin account
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')
                ->with('message', 'You cannot delete your own account.')
                ->with('message_type', 'warning');
        }

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
        // Get the logged-in user's ID from session
        $user_id = session()->get('user_id');
        if (!$user_id) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($user_id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('profile', ['user' => $user]);
    }

    public function updateProfile()
    {
        // Get the logged-in user's ID from session
        $user_id = session()->get('user_id');
        if (!$user_id) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($user_id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Validate the input
        if (!$this->validate([
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'middle_name' => 'permit_empty|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,user_id,{$user_id}]",
            'gender' => 'permit_empty|in_list[male,female,other]',
            'birthdate' => 'permit_empty|valid_date',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
            'current_password' => 'permit_empty|min_length[8]',
            'new_password' => 'permit_empty|min_length[8]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validation)
                ->with('message', 'Please check the form for errors')
                ->with('message_type', 'danger');
        }

        // Prepare update data
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $this->request->getPost('email'),
            'gender' => $this->request->getPost('gender'),
            'birthdate' => $this->request->getPost('birthdate')
        ];

        // Handle password update if provided
        $current_password = $this->request->getPost('current_password');
        $new_password = $this->request->getPost('new_password');

        if (!empty($current_password) && !empty($new_password)) {
            // Verify current password
            if (!password_verify($current_password, $user['password'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('validation', $this->validation)
                    ->with('message', 'Current password is incorrect')
                    ->with('message_type', 'danger');
            }
            $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/users';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old image if exists
            if (!empty($user['image']) && file_exists($uploadPath . '/' . $user['image'])) {
                unlink($uploadPath . '/' . $user['image']);
            }
            
            $imageName = $image->getRandomName();
            $image->move($uploadPath, $imageName);
            $data['image'] = $imageName;
        }

        // Handle image removal
        if ($this->request->getPost('remove_image') === '1') {
            $uploadPath = WRITEPATH . 'uploads/profile';
            if (!empty($user['image']) && file_exists($uploadPath . '/' . $user['image'])) {
                unlink($uploadPath . '/' . $user['image']);
            }
            $data['image'] = null;
        }

        // Update user
        if ($this->userModel->update($user_id, $data)) {
            // Update session data
            $updatedUser = $this->userModel->find($user_id);
            $sessionData = [
                'user_id' => $updatedUser['user_id'],
                'first_name' => $updatedUser['first_name'],
                'last_name' => $updatedUser['last_name'],
                'email' => $updatedUser['email'],
                'role' => $updatedUser['role'],
                'image' => $updatedUser['image']
            ];
            session()->set($sessionData);

            return redirect()->to('/profile')
                ->with('message', 'Profile updated successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('validation', $this->validation)
            ->with('message', 'Failed to update profile')
            ->with('message_type', 'danger');
    }
}
