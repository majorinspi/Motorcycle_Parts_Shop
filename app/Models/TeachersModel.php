<?php

namespace App\Models;

use CodeIgniter\Model;

class TeachersModel extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';

    protected $allowedFields = ['first_name','last_name','email'];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('first_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('email', $searchValue)
                ->groupEnd();
        }

        // Clone builder for filtered count before applying limit
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
