<?php

namespace App\Models;

use CodeIgniter\Model;

class RestockRequestsModel extends Model
{
    protected $table = 'restock_requests';
    protected $primaryKey = 'request_id';

    protected $allowedFields = ['product_id', 'supplier_id', 'quantity_requested', 'status', 'request_date'];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->builder();
        $builder->select('restock_requests.*, products.product_name, suppliers.supplier_name, suppliers.contact_email');
        $builder->join('products', 'products.product_id = restock_requests.product_id', 'left');
        $builder->join('suppliers', 'suppliers.supplier_id = restock_requests.supplier_id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('products.product_name', $searchValue)
                ->orLike('suppliers.supplier_name', $searchValue)
                ->orLike('restock_requests.status', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->orderBy('restock_requests.request_date', 'DESC');
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
