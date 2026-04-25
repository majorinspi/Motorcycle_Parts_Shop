<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProductsModel;
use App\Models\CustomersModel;
use App\Models\TransactionsModel;
use App\Models\LogModel;

class Pos extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $productsModel = new ProductsModel();
        $customersModel = new CustomersModel();

        // Fetch products with stock > 0
        $data['products'] = $productsModel->where('current_stock >', 0)->findAll();
        $data['customers'] = $customersModel->findAll();

        return view('pos/index', $data);
    }

    public function checkout()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized access.']);
        }

        $transactionsModel = new TransactionsModel();
        $productsModel = new ProductsModel();
        $logModel = new LogModel();

        $cart = $this->request->getPost('cart'); // JSON string
        $customerId = $this->request->getPost('customer_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $amountPaid = $this->request->getPost('amount_paid');
        $totalAmount = $this->request->getPost('total_amount');

        if (empty($cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart is empty.']);
        }

        if (is_string($cart)) {
            $cart = json_decode($cart, true);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($cart as $item) {
            $product = $productsModel->find($item['product_id']);
            if (!$product || $product['current_stock'] < $item['quantity']) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Insufficient stock for ' . ($product ? $product['product_name'] : 'Unknown Product')]);
            }

            $transactionData = [
                'product_id' => $item['product_id'],
                'customer_id' => empty($customerId) ? null : $customerId,
                'type' => 'Out',
                'quantity' => $item['quantity'],
                'total_amount' => $item['price'] * $item['quantity'],
                'payment_method' => empty($paymentMethod) ? 'Cash' : $paymentMethod,
                'date' => date('Y-m-d H:i:s')
            ];

            $transactionsModel->insert($transactionData);

            // Deduct stock
            $newStock = $product['current_stock'] - $item['quantity'];
            $productsModel->update($item['product_id'], ['current_stock' => $newStock]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Checkout failed. Please try again.']);
        }

        $logModel->addLog('POS Checkout completed. Total: ₱' . number_format($totalAmount, 2), 'ADD');
        return $this->response->setJSON(['status' => 'success', 'message' => 'Transaction successful!']);
    }
}
