<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\CategoriesModel;

class Categories extends Controller
{
    public function index(){
        $model = new CategoriesModel();
        $data['categories'] = $model->findAll();
        return view('categories/index', $data);
    }

    public function save(){
        $category_name = $this->request->getPost('category_name');
        $categoryModel = new \App\Models\CategoriesModel();
        $logModel = new LogModel();

        $data = [
            'category_name' => $category_name,
        ];

        if ($categoryModel->insert($data)) {
            $logModel->addLog('New Category has been added: ' . $category_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save category']);
        }
    }

    public function update(){
        $model = new CategoriesModel();
        $logModel = new LogModel();
        $categoryId = $this->request->getPost('category_id');
        $category_name = $this->request->getPost('category_name');
     

        $userData = [
            'category_name' => $category_name,
        ];

        $updated = $model->update($categoryId, $userData);

        if ($updated) {
            $logModel->addLog('New Category has been updated: ' . $category_name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating category.'
            ]);
        }
    }

    public function edit($category_id){
        $model = new CategoriesModel();
    $category = $model->find($category_id); // Fetch category by ID

    if ($category) {
        return $this->response->setJSON(['data' => $category]); // Return category data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Category not found']);
    }
}

public function delete($category_id){
    $model = new CategoriesModel();
    $logModel = new LogModel();
    $category = $model->find($category_id);
    if (!$category) {
        return $this->response->setJSON(['success' => false, 'message' => 'Category not found.']);
    }

    $deleted = $model->delete($category_id);

    if ($deleted) {
        $logModel->addLog('Delete category', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Category deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Customer.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\CategoriesModel();

    $start = $request->getPost('start') ?? 0;
    $length = $request->getPost('length') ?? 10;
    $searchValue = $request->getPost('search')['value'] ?? '';

    $totalRecords = $model->countAll();
    $result = $model->getRecords($start, $length, $searchValue);

    $data = [];
    $counter = $start + 1;
    foreach ($result['data'] as $row) {
        $row['row_number'] = $counter++;
        $data[] = $row;
    }

    return $this->response->setJSON([
        'draw' => intval($request->getPost('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $result['filtered'],
        'data' => $data,
    ]);
}

}
