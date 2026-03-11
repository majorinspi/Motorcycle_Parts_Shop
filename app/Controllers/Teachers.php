<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\TeachersModel;

class Teachers extends Controller
{
    public function index(){
        $model = new TeachersModel();
        $data['teachers'] = $model->findAll();
        return view('teachers/index', $data);
    }

    public function save(){
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');

        $userModel = new \App\Models\TeachersModel();
        $logModel = new LogModel();

        $data = [
            'first_name'       => $first_name,
            'last_name'      => $last_name,
            'email'      => $email
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Teachers has been added: ' . $first_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save teachers.']);
        }
    }

    public function update(){
        $model = new TeachersModel();
        $logModel = new LogModel();
        $userId = $this->request->getPost('id');
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');

        $userData = [
            'first_name'       => $first_name,
            'last_name'      => $last_name,
            'email'      => $email
        ];

        $updated = $model->update($userId, $userData);

        if ($updated) {
            $logModel->addLog('New Teachers has been updated: ' . $first_name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Teachers updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating user.'
            ]);
        }
    }

    public function edit($id){
        $model = new TeachersModel();
    $user = $model->find($id); // Fetch user by ID

    if ($user) {
        return $this->response->setJSON(['data' => $user]); // Return user data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }
}

public function delete($id){
    $model = new TeachersModel();
    $logModel = new LogModel();
    $user = $model->find($id);
    if (!$user) {
        return $this->response->setJSON(['success' => false, 'message' => 'Teacher not found.']);
    }

    $deleted = $model->delete($id);

    if ($deleted) {
        $logModel->addLog('Delete teachers', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Teachers deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Teachers.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\TeachersModel();

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
