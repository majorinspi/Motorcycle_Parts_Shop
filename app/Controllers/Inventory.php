<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Inventory extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        $currentMonth = date('m');
        $currentYear = date('Y');

        $sql = "
            SELECT p.product_id, p.product_name, p.brand, p.current_stock, c.category_name,
                   COALESCE(SUM(t.quantity), 0) as total_sold
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN transactions t ON p.product_id = t.product_id 
                                     AND t.type = 'Out' 
                                     AND MONTH(t.date) = ? 
                                     AND YEAR(t.date) = ?
            GROUP BY p.product_id
        ";

        $query = $db->query($sql, [$currentMonth, $currentYear]);
        $results = $query->getResultArray();

        $soldItems = [];
        $unsoldItems = [];

        foreach ($results as $row) {
            if ($row['total_sold'] > 0) {
                $soldItems[] = $row;
            } else {
                $unsoldItems[] = $row;
            }
        }

        $data = [
            'soldItems' => $soldItems,
            'unsoldItems' => $unsoldItems,
            'currentMonthName' => date('F Y')
        ];

        return view('inventory/index', $data);
    }
}
