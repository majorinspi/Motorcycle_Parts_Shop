<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\CustomersModel;

class Customers extends Controller
{
    public function index(){
        $model = new CustomersModel();
        $data['customers'] = $model->findAll();
        return view('customers/index', $data);
    }

    public function save(){
        $customer_name = $this->request->getPost('customer_name');
        $contact_number = $this->request->getPost('contact_number');
        $address = $this->request->getPost('address');
        $email = $this->request->getPost('email');
        $customersModel = new \App\Models\CustomersModel();
        $logModel = new LogModel();

        $data = [
            'customer_name' => $customer_name,
            'contact_number' => $contact_number,
            'address' => $address,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s')

        ];

        if ($customersModel->insert($data)) {
            $logModel->addLog('New Customer has been added: ' . $customer_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save customer']);
        }
    }

    public function update(){
        $model = new CustomersModel();
        $logModel = new LogModel();
        $customerId = $this->request->getPost('customer_id');
        $customer_name = $this->request->getPost('customer_name');
        $contact_number = $this->request->getPost('contact_number');
        $address = $this->request->getPost('address');
        $email = $this->request->getPost('email');  

        $userData = [
            'customer_name' => $customer_name,
            'contact_number' => $contact_number,
            'address' => $address,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $model->update($customerId, $userData);

        if ($updated) {
            $logModel->addLog('New Customer has been updated: ' . $customer_name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Customer updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating customer.'
            ]);
        }
    }

    public function edit($customer_id){
        $model = new CustomersModel();
    $customer = $model->find($customer_id); // Fetch customer by ID

    if ($customer) {
        return $this->response->setJSON(['data' => $customer]); // Return customer data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Customer not found']);
    }
}

public function delete($customer_id){
    $model = new CustomersModel();
    $logModel = new LogModel();
    $customer = $model->find($customer_id);
    if (!$customer) {
        return $this->response->setJSON(['success' => false, 'message' => 'Customer not found.']);
    }

    $deleted = $model->delete($customer_id);

    if ($deleted) {
        $logModel->addLog('Delete customer', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Customer deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Customer.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\CustomersModel();

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
