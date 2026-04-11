<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\SuppliersModel;

class Suppliers extends Controller
{
    public function index(){
        $model = new SuppliersModel();
        $data['suppliers'] = $model->findAll();
        return view('suppliers/index', $data);
    }

    public function save(){
        $supplier_name = $this->request->getPost('supplier_name');
        $contact_email = $this->request->getPost('contact_email');
        $suppliersModel = new \App\Models\SuppliersModel();
        $logModel = new LogModel();

        $data = [
            'supplier_name' => $supplier_name,
            'contact_email' => $contact_email
        ];

        if ($suppliersModel->insert($data)) {
            $logModel->addLog('New Supplier has been added: ' . $supplier_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save category']);
        }
    }

    public function update(){
        $model = new SuppliersModel();
        $logModel = new LogModel();
        $supplierId = $this->request->getPost('supplier_id');
        $supplier_name = $this->request->getPost('supplier_name');
        $contact_email = $this->request->getPost('contact_email');
     

        $userData = [
            'supplier_name' => $supplier_name,
            'contact_email' => $contact_email
        ];

        $updated = $model->update($supplierId, $userData);

        if ($updated) {
            $logModel->addLog('New Supplier has been updated: ' . $supplier_name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Supplier updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating supplier.'
            ]);
        }
    }

    public function edit($supplier_id){
        $model = new SuppliersModel();
    $supplier = $model->find($supplier_id); // Fetch supplier by ID

    if ($supplier) {
        return $this->response->setJSON(['data' => $supplier]); // Return supplier data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Supplier not found']);
    }
}

public function delete($supplier_id){
    $model = new SuppliersModel();
    $logModel = new LogModel();
    $supplier = $model->find($supplier_id);
    if (!$supplier) {
        return $this->response->setJSON(['success' => false, 'message' => 'Supplier not found.']);
    }

    $deleted = $model->delete($supplier_id);

    if ($deleted) {
        $logModel->addLog('Delete supplier', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Supplier deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Customer.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\SuppliersModel();

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
