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

        // Total Sales (sum of total_amount for Out transactions over all time)
        $totalSalesQuery = $transactionsModel->selectSum('total_amount')->where('type', 'Out')->first();
        $totalSales = $totalSalesQuery ? $totalSalesQuery['total_amount'] : 0;

        $currentMonth = date('m');
        $currentYear = date('Y');

        // Monthly Sales Revenue
        $monthlyRevenueQuery = $transactionsModel->selectSum('total_amount')
            ->where('type', 'Out')
            ->where('MONTH(date)', $currentMonth)
            ->where('YEAR(date)', $currentYear)
            ->first();
        $monthlyRevenue = $monthlyRevenueQuery && $monthlyRevenueQuery['total_amount'] ? $monthlyRevenueQuery['total_amount'] : 0;

        // Monthly Inventory Sales (Qty sold)
        $monthlyInventorySalesQuery = $transactionsModel->selectSum('quantity')
            ->where('type', 'Out')
            ->where('MONTH(date)', $currentMonth)
            ->where('YEAR(date)', $currentYear)
            ->first();
        $monthlyInventorySales = $monthlyInventorySalesQuery && $monthlyInventorySalesQuery['quantity'] ? $monthlyInventorySalesQuery['quantity'] : 0;

        // Total inventory to determine unsale items
        $totalStockQuery = $productsModel->selectSum('current_stock')->first();
        $totalCurrentStock = $totalStockQuery && $totalStockQuery['current_stock'] ? $totalStockQuery['current_stock'] : 0;

        // Revenue chart data (Last 7 days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('D', strtotime($date));
            
            $dayRevenueQuery = $transactionsModel->selectSum('total_amount')
                ->where('type', 'Out')
                ->where('DATE(date)', $date)
                ->first();
            $chartData[] = $dayRevenueQuery && $dayRevenueQuery['total_amount'] ? (float)$dayRevenueQuery['total_amount'] : 0;
        }

        $data = [
            'activeCatalogCount' => $activeCatalogCount,
            'criticalLowStockCount' => $criticalLowStockCount,
            'totalSales' => $totalSales,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyInventorySales' => $monthlyInventorySales,
            'totalCurrentStock' => $totalCurrentStock,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
        ];

        return view('dashboard', $data);
    }
}
