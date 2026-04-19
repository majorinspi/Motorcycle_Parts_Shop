<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\TransactionsModel;
use App\Models\CustomersModel;

class Transactions extends Controller
{
    public function index()
    {
        $model = new TransactionsModel();
        $productsModel = new \App\Models\ProductsModel();
        $customersModel = new \App\Models\CustomersModel();

        $data['transactions'] = $model->findAll();
        $data['products'] = $productsModel->findAll();
        $data['customers'] = $customersModel->findAll();
        return view('transactions/index', $data);
    }

    public function save()
    {
        $product_id = $this->request->getPost('product_id');
        $customer_id = $this->request->getPost('customer_id');
        $type = $this->request->getPost('type');
        $quantity = $this->request->getPost('quantity');
        $date = $this->request->getPost('date');
        $payment_method = $this->request->getPost('payment_method');

        $transactionsModel = new \App\Models\TransactionsModel();
        $productsModel = new \App\Models\ProductsModel();
        $logModel = new LogModel();

        $total_amount = 0;
        if ($type === 'Out') {
            $product = $productsModel->find($product_id);
            if ($product) {
                $total_amount = $product['unit_price'] * $quantity;
            }
        } else {
            $payment_method = null;
        }

        $data = [
            'product_id' => $product_id,
            'customer_id' => empty($customer_id) ? null : $customer_id,
            'type' => $type,
            'quantity' => $quantity,
            'total_amount' => $total_amount,
            'payment_method' => empty($payment_method) ? null : $payment_method,
            'date' => date('Y-m-d H:i:s')
        ];

        if ($transactionsModel->insert($data)) {
            // Update stock
            $product = $productsModel->find($product_id);
            if ($product) {
                $newStock = ($type === 'In') ? $product['current_stock'] + $quantity : $product['current_stock'] - $quantity;
                $productsModel->update($product_id, ['current_stock' => $newStock]);
            }

            $logModel->addLog('New Transaction has been added: ' . $product_id, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save transaction']);
        }
    }

    public function update()
    {
        $model = new TransactionsModel();
        $productsModel = new \App\Models\ProductsModel();
        $logModel = new LogModel();

        $transactionId = $this->request->getPost('transaction_id');
        $product_id = $this->request->getPost('product_id');
        $customer_id = $this->request->getPost('customer_id');
        $type = $this->request->getPost('type');
        $quantity = $this->request->getPost('quantity');
        $date = $this->request->getPost('date');
        $payment_method = $this->request->getPost('payment_method');

        $total_amount = 0;
        if ($type === 'Out') {
            $product = $productsModel->find($product_id);
            if ($product) {
                $total_amount = $product['unit_price'] * $quantity;
            }
        } else {
            $payment_method = null;
        }

        $userData = [
            'product_id' => $product_id,
            'customer_id' => empty($customer_id) ? null : $customer_id,
            'type' => $type,
            'quantity' => $quantity,
            'total_amount' => $total_amount,
            'payment_method' => empty($payment_method) ? null : $payment_method,
            'date' => date('Y-m-d H:i:s')
        ];

        // Fetch old transaction to revert its effect on stock
        $oldTransaction = $model->find($transactionId);
        if ($oldTransaction) {
            $oldProduct = $productsModel->find($oldTransaction['product_id']);
            if ($oldProduct) {
                $revertedStock = ($oldTransaction['type'] === 'In') ? $oldProduct['current_stock'] - $oldTransaction['quantity'] : $oldProduct['current_stock'] + $oldTransaction['quantity'];
                $productsModel->update($oldProduct['product_id'], ['current_stock' => $revertedStock]);
            }
        }

        $updated = $model->update($transactionId, $userData);

        if ($updated) {
            // Apply new transaction effect on stock
            $newProduct = $productsModel->find($product_id);
            if ($newProduct) {
                $newStock = ($type === 'In') ? $newProduct['current_stock'] + $quantity : $newProduct['current_stock'] - $quantity;
                $productsModel->update($product_id, ['current_stock' => $newStock]);
            }

            $logModel->addLog('Transaction has been updated: ' . $product_id, 'UPDATED');
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

    public function edit($transaction_id)
    {
        $model = new TransactionsModel();
        $transaction = $model->find($transaction_id); // Fetch transaction by ID

        if ($transaction) {
            return $this->response->setJSON(['data' => $transaction]); // Return transaction data as JSON
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Transaction not found']);
        }
    }

    public function delete($transaction_id)
    {
        $model = new TransactionsModel();
        $productsModel = new \App\Models\ProductsModel();
        $logModel = new LogModel();
        $transaction = $model->find($transaction_id);
        if (!$transaction) {
            return $this->response->setJSON(['success' => false, 'message' => 'Transaction not found.']);
        }

        $deleted = $model->delete($transaction_id);

        if ($deleted) {
            // Revert transaction effect on stock
            $product = $productsModel->find($transaction['product_id']);
            if ($product) {
                $revertedStock = ($transaction['type'] === 'In') ? $product['current_stock'] - $transaction['quantity'] : $product['current_stock'] + $transaction['quantity'];
                $productsModel->update($product['product_id'], ['current_stock' => $revertedStock]);
            }

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
