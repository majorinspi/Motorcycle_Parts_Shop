<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\ProductsModel;
use App\Models\CategoriesModel;

class Products extends Controller
{
    public function index(){
        $model = new ProductsModel();
        $categoryModel = new CategoriesModel();
        $categoryId = $this->request->getGet('category_id');
        
        if ($categoryId) {
            $data['products'] = $model->where('category_id', $categoryId)->findAll();
            $data['filter_category'] = $categoryId;
        } else {
            $data['products'] = $model->findAll();
        }
        
        $data['categories'] = $categoryModel->findAll();
        return view('products/index', $data);
    }

    public function save(){
        $product_name = $this->request->getPost('product_name');
        $category_id = $this->request->getPost('category_id');
        $current_stock = $this->request->getPost('current_stock');
        $reorder_level = $this->request->getPost('reorder_level');
        $productsModel = new \App\Models\ProductsModel();
        $logModel = new LogModel();
        $transactionsModel = new \App\Models\TransactionsModel();

        $data = [
            'product_name' => $product_name,
            'category_id' => $category_id,
            'current_stock' => $current_stock,
            'reorder_level' => $reorder_level   
        ];

        if ($productsModel->insert($data)) {
            $productId = $productsModel->getInsertID();

            // If initial stock is set, create transaction
            if ($current_stock > 0) {
                $transactionData = [
                    'product_id' => $productId,
                    'type' => 'In',
                    'quantity' => $current_stock,
                    'date' => date('Y-m-d')
                ];
                $transactionsModel->insert($transactionData);
            }

            $logModel->addLog('New Product has been added: ' . $product_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save product']);
        }
    }

    public function update(){
        $model = new ProductsModel();
        $logModel = new LogModel();
        $transactionsModel = new \App\Models\TransactionsModel();
        $productId = $this->request->getPost('product_id');
        $product_name = $this->request->getPost('product_name');
        $category_id = $this->request->getPost('category_id');  
        $current_stock = $this->request->getPost('current_stock');
        $reorder_level = $this->request->getPost('reorder_level');      

        // Get the existing product to check stock change
        $existingProduct = $model->find($productId);
        $old_stock = $existingProduct['current_stock'];

        $userData = [
            'product_name' => $product_name,
            'category_id' => $category_id,
            'current_stock' => $current_stock,
            'reorder_level' => $reorder_level
        ];

        $updated = $model->update($productId, $userData);

        if ($updated) {
            // Check if stock changed
            if ($old_stock != $current_stock) {
                $stock_change = $current_stock - $old_stock;
                $type = $stock_change > 0 ? 'In' : 'Out';
                $quantity = abs($stock_change);

                $transactionData = [
                    'product_id' => $productId,
                    'type' => $type,
                    'quantity' => $quantity,
                    'date' => date('Y-m-d')
                ];

                $transactionsModel->insert($transactionData);
            }

            $logModel->addLog('Product has been updated: ' . $product_name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating product.'
            ]);
        }
    }

    public function edit($product_id){
        $model = new ProductsModel();
        $product = $model->find($product_id); // Fetch product by ID

    if ($product) {
        return $this->response->setJSON(['data' => $product]); // Return product data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Product not found']);
    }
}

public function delete($product_id){
    $model = new ProductsModel();
    $logModel = new LogModel();
    $product = $model->find($product_id);
    if (!$product) {
        return $this->response->setJSON(['success' => false, 'message' => 'Product not found.']);
    }

    $deleted = $model->delete($product_id);

    if ($deleted) {
        $logModel->addLog('Delete product', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Product deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Product.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\ProductsModel();
    $categoryId = $request->getGet('category_id');

    $start = $request->getPost('start') ?? 0;
    $length = $request->getPost('length') ?? 10;
    $searchValue = $request->getPost('search')['value'] ?? '';

    if ($categoryId) {
        $totalRecords = $model->where('category_id', $categoryId)->countAllResults();
    } else {
        $totalRecords = $model->countAll();
    }
    
    $result = $model->getRecords($start, $length, $searchValue, $categoryId);

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
