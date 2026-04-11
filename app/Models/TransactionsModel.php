<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';

    protected $allowedFields = ['product_id', 'type', 'quantity', 'date'];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('transactions.*, products.product_name');
        $builder->join('products', 'products.product_id = transactions.product_id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('transactions.transaction_id', $searchValue)
                ->orLike('products.product_name', $searchValue)
                ->orLike('transactions.type', $searchValue)
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
