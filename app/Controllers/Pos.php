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
        $amountPaid = floatval($this->request->getPost('amount_paid'));
        $totalAmount = floatval($this->request->getPost('total_amount'));

        $paymentMethod = empty($paymentMethod) ? 'Cash' : $paymentMethod;

        if (empty($cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart is empty.']);
        }

        if (is_string($cart)) {
            $cart = json_decode($cart, true);
            if ($cart === null || !is_array($cart)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid cart data.']);
            }
        }

        $validatedCart = [];
        $computedTotal = 0;

        foreach ($cart as $item) {
            if (!isset($item['product_id'], $item['quantity'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid cart item.']);
            }

            $productId = intval($item['product_id']);
            $quantity = intval($item['quantity']);

            if ($quantity <= 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid item quantity.']);
            }

            $product = $productsModel->find($productId);
            if (!$product || $product['current_stock'] < $quantity) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Insufficient stock for ' . ($product ? $product['product_name'] : 'Unknown Product')]);
            }

            $unitPrice = floatval($product['unit_price']);
            $itemTotal = $unitPrice * $quantity;
            $computedTotal += $itemTotal;

            $validatedCart[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'product_name' => $product['product_name'],
                'current_stock' => $product['current_stock']
            ];
        }

        if ($computedTotal <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart total must be greater than zero.']);
        }

        if ($amountPaid < $computedTotal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Amount paid is less than total amount.']);
        }

        $totalAmount = $computedTotal;

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($validatedCart as $item) {
            $transactionData = [
                'product_id' => $item['product_id'],
                'customer_id' => empty($customerId) ? null : $customerId,
                'type' => 'Out',
                'quantity' => $item['quantity'],
                'total_amount' => $item['unit_price'] * $item['quantity'],
                'payment_method' => empty($paymentMethod) ? 'Cash' : $paymentMethod,
                'date' => date('Y-m-d H:i:s')
            ];

            $transactionsModel->insert($transactionData);

            // Deduct stock
            $newStock = $item['current_stock'] - $item['quantity'];
            $productsModel->update($item['product_id'], ['current_stock' => $newStock]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Checkout failed. Please try again.']);
        }

        session()->setFlashdata('receipt_data', [
            'cart' => $cart,
            'total' => $totalAmount,
            'paid' => $amountPaid,
            'change' => $amountPaid - $totalAmount,
            'customer_id' => $customerId,
            'payment_method' => $paymentMethod,
            'date' => date('Y-m-d H:i:s')
        ]);

        $logModel->addLog('POS Checkout completed. Total: ₱' . number_format($totalAmount, 2), 'ADD');
        return $this->response->setJSON(['status' => 'success', 'message' => 'Transaction successful!', 'redirect' => base_url('pos/receipt')]);
    }

    public function receipt()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $receiptData = session()->getFlashdata('receipt_data');
        if (!$receiptData) {
            return redirect()->to('/pos');
        }

        $data['receipt'] = $receiptData;
        $data['customer_name'] = 'Walk-in Customer';

        if (!empty($receiptData['customer_id'])) {
            $customersModel = new CustomersModel();
            $customer = $customersModel->find($receiptData['customer_id']);
            if ($customer) {
                $data['customer_name'] = $customer['customer_name'];
            }
        }

        // Keep it in flashdata in case of refresh (optional, but good for printing)
        session()->setFlashdata('receipt_data', $receiptData);

        return view('pos/receipt', $data);
    }
}
