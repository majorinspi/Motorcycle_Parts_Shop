<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $allowedFields = ['product_name', 'brand', 'category_id', 'current_stock', 'unit_price'];

    public function getRecords($start, $length, $searchValue = '', $categoryId = null)
    {
        $builder = $this->builder();
        $builder->select('products.*, categories.category_name');
        $builder->join('categories', 'categories.category_id = products.category_id', 'left');

        if ($categoryId) {
            $builder->where('products.category_id', $categoryId);
        }

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('product_name', $searchValue)
                ->orLike('categories.category_name', $searchValue)
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
