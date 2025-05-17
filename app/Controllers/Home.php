<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\BlogModel;

class Home extends BaseController
{
    protected $userModel;
    protected $categoryModel;
    protected $blogModel;
    
    public function __construct()
    {
        $this->userModel = model(UserModel::class);
        $this->categoryModel = model(CategoryModel::class);
        $this->blogModel = model(BlogModel::class);
    }
    
    public function index(): string
    {
        return view('index');
    }

    public function about()
    {
        return view('about');
    }

    public function dashboard()
    {
        $data = [
            'users_count' => $this->userModel->countAllResults(),
            'categories_count' => $this->categoryModel->countAllResults(),
            'published_blogs_count' => $this->blogModel->where('visibility', 'public')->countAllResults(),
            'unpublished_blogs_count' => $this->blogModel->where('visibility', 'private')->countAllResults(),
        ];
        return view('dashboard', $data);
    }
}
