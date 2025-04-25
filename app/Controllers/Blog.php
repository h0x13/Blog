<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BlogModel;

class Blog extends BaseController
{
    protected $blogModel;
    protected $validation;

    public function __construct()
    {
        $this->blogModel = model(BlogModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'blogs' => $this->blogModel->select('blogs.*, users.first_name, users.last_name, users.middle_name')
                                       ->join('users', 'users.user_id = blogs.user_id')
                                       ->findAll(),
            'validation' => $this->validation
        ];
        return view('blogs', $data);
    }
}
