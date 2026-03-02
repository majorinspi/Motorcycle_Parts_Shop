<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentsModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name','gender','address'];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('name', $searchValue)
                ->orLike('gender', $searchValue)
                ->orLike('address', $searchValue)
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
