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
        $id = 2;
        $user = $this->userModel->find($id);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }
        return view('profile', $user);
    }
}
