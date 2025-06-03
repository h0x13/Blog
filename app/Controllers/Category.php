<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\AuditLogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Category extends BaseController
{
    protected $categoryModel;
    protected $auditLogModel;
    protected $validation;

    public function __construct()
    {
        $this->categoryModel = model(CategoryModel::class);
        $this->auditLogModel = model(AuditLogModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $data['categories'] = $this->categoryModel->findAll();
        return view('categories/index', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        if (!$this->validate([
            'name' => 'required|min_length[2]|max_length[50]|is_unique[categories.name]',
            'description' => 'permit_empty|max_length[255]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->categoryModel->insert($data)) {
            // Log the category creation
            $this->auditLogModel->log(
                session()->get('user_id'),
                'CREATE',
                'CATEGORY',
                $this->categoryModel->getInsertID(),
                'Created new category: ' . $data['name']
            );

            return redirect()->to('/categories')
                ->with('message', 'Category created successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to create category')
            ->with('message_type', 'danger');
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate([
            'name' => "required|min_length[2]|max_length[50]|is_unique[categories.name,category_id,$id]",
            'description' => 'permit_empty|max_length[255]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->categoryModel->update($id, $data)) {
            // Log the category update
            $this->auditLogModel->log(
                session()->get('user_id'),
                'UPDATE',
                'CATEGORY',
                $id,
                'Updated category: ' . $data['name']
            );

            return redirect()->to('/categories')
                ->with('message', 'Category updated successfully')
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to update category')
            ->with('message_type', 'danger');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->categoryModel->delete($id)) {
            // Log the category deletion
            $this->auditLogModel->log(
                session()->get('user_id'),
                'DELETE',
                'CATEGORY',
                $id,
                'Deleted category: ' . $category['name']
            );

            return redirect()->to('/categories')
                ->with('message', 'Category deleted successfully')
                ->with('message_type', 'success');
        }

        return redirect()->to('/categories')
            ->with('message', 'Failed to delete category')
            ->with('message_type', 'danger');
    }
}
