<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\TransactionsModel;

class Transactions extends Controller
{
    public function index(){
        $model = new TransactionsModel();
        $productsModel = new \App\Models\ProductsModel();
        $data['transactions'] = $model->findAll();
        $data['products'] = $productsModel->findAll();
        return view('transactions/index', $data);
    }

    public function save(){
        $product_id = $this->request->getPost('product_id');
        $type = $this->request->getPost('type');
        $quantity = $this->request->getPost('quantity');
        $date = $this->request->getPost('date');
        $transactionsModel = new \App\Models\TransactionsModel();
        $logModel = new LogModel();

        $data = [
            'product_id' => $product_id,
            'type' => $type,
            'quantity' => $quantity,
            'date' => $date
        ];

        if ($transactionsModel->insert($data)) {
            $logModel->addLog('New Transaction has been added: ' . $product_id, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save category']);
        }
    }

    public function update(){
        $model = new TransactionsModel();
        $logModel = new LogModel();
        $transactionId = $this->request->getPost('transaction_id');
        $product_id = $this->request->getPost('product_id');
        $type = $this->request->getPost('type');
        $quantity = $this->request->getPost('quantity');
        $date = $this->request->getPost('date');    

        $userData = [
            'product_id' => $product_id,
            'type' => $type,
            'quantity' => $quantity,
            'date' => $date
        ];

        $updated = $model->update($transactionId, $userData);

        if ($updated) {
            $logModel->addLog('New Transaction has been updated: ' . $product_id, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaction updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating transaction.'
            ]);
        }
    }

    public function edit($transaction_id){
        $model = new TransactionsModel();
    $transaction = $model->find($transaction_id); // Fetch transaction by ID

    if ($transaction) {
        return $this->response->setJSON(['data' => $transaction]); // Return transaction data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Transaction not found']);
    }
}

public function delete($transaction_id){
    $model = new TransactionsModel();
    $logModel = new LogModel();
    $transaction = $model->find($transaction_id);
    if (!$transaction) {
        return $this->response->setJSON(['success' => false, 'message' => 'Transaction not found.']);
    }

    $deleted = $model->delete($transaction_id);

    if ($deleted) {
        $logModel->addLog('Delete transaction', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Transaction deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Transaction.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\TransactionsModel();

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
