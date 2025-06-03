<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Category extends BaseController
{
    protected $categoryModel;
    protected $validation;

    public function __construct()
    {
        $this->categoryModel = model(CategoryModel::class);
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'categories' => $this->categoryModel->findAll(),
            'validation' => $this->validation
        ];
        return view('categories', $data);
    }

    public function add() {
        if (!$this->validate(['name' => 'required|min_length[2]|max_length[255]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/categories')
                ->with('message', "{$data['name']} added successfully")
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to add category')
            ->with('message_type', 'danger');
    }

    public function edit($id) {
        if (!$this->validate(['name' => 'required|min_length[2]|max_length[255]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
        ];

        $categoryName = $this->categoryModel->find($id);
        if (!$categoryName) {
            throw PageNotFoundException::forPageNotFound();
        }


        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/categories')
                ->with('message', "{$data['name']} updated successfully")
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to update category')
            ->with('message_type', 'danger');
    }


    public function delete($id) {
        $categoryName = $this->categoryModel->find($id);

        if (!$categoryName) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/categories')
                ->with('message', "Category deleted successfully")
                ->with('message_type', 'success');
        }

        return redirect()->back()
            ->withInput()
            ->with('message', 'Failed to delete category')
            ->with('message_type', 'danger');
    }
}
