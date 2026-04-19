<?php

namespace App\Controllers;

use App\Models\ProductsModel;
use App\Models\TransactionsModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $productsModel = new ProductsModel();
        $transactionsModel = new TransactionsModel();

        // Active Parts Catalog
        $activeCatalogCount = $productsModel->countAllResults();

        // Critical Low Stock
        $criticalLowStockCount = $productsModel->where('current_stock <=', 10)->countAllResults();

        // Total Sales (sum of total_amount for Out transactions)
        $totalSalesQuery = $transactionsModel->selectSum('total_amount')->where('type', 'Out')->first();
        $totalSales = $totalSalesQuery ? $totalSalesQuery['total_amount'] : 0;

        $data = [
            'activeCatalogCount' => $activeCatalogCount,
            'criticalLowStockCount' => $criticalLowStockCount,
            'totalSales' => $totalSales,
        ];

        return view('dashboard', $data);
    }
}
