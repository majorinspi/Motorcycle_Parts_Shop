<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\ParentsModel;

class Parents extends Controller
{
    public function index(){
        $model = new ParentsModel();
        $data['parents'] = $model->findAll();
        return view('parents/index', $data);
    }

    public function save(){
        $name = $this->request->getPost('name');
        $gender= $this->request->getPost('gender');
        $address= $this->request->getPost('address');

        $userModel = new \App\Models\ParentsModel();
        $logModel = new LogModel();

        $data = [
            'name'       => $name,
            'gender'      => $gender,
            'address'      => $address
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Parents has been added: ' . $name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save parents']);
        }
    }

    public function update(){
        $model = new ParentsModel();
        $logModel = new LogModel();
        $userId = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $gender = $this->request->getPost('gender');
        $address = $this->request->getPost('address');

        $userData = [
            'name'       => $name,
            'gender'      => $gender,
            'address'      => $address,
        ];

        $updated = $model->update($userId, $userData);

        if ($updated) {
            $logModel->addLog('New Parents has been updated: ' . $name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Parents updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating user.'
            ]);
        }
    }

    public function edit($id){
        $model = new ParentsModel();
    $user = $model->find($id); // Fetch user by ID

    if ($user) {
        return $this->response->setJSON(['data' => $user]); // Return user data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }
}

public function delete($id){
    $model = new ParentsModel();
    $logModel = new LogModel();
    $user = $model->find($id);
    if (!$user) {
        return $this->response->setJSON(['success' => false, 'message' => 'Person not found.']);
    }

    $deleted = $model->delete($id);

    if ($deleted) {
        $logModel->addLog('Delete parents', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Parents deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Parents.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\ParentsModel();

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
