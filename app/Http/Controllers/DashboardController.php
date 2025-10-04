<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalSales = Sale::count();

        // Cards do dashboard
        $data['cards'] = [
            [
                'label' => 'Produtos',
                'value' => $totalProducts,
                'icon' => 'fas fa-box',
                'color' => 'primary'
            ],
            [
                'label' => 'Clientes',
                'value' => $totalCustomers,
                'icon' => 'fas fa-users',
                'color' => 'success'
            ],
            [
                'label' => 'Pedidos',
                'value' => $totalOrders,
                'icon' => 'fas fa-shopping-cart',
                'color' => 'warning'
            ],
            [
                'label' => 'Vendas',
                'value' => $totalSales,
                'icon' => 'fas fa-dollar-sign',
                'color' => 'info'
            ]
        ];

        // Produtos com baixo estoque (assumindo estoque < 10)
        $data['lowStockProducts'] = Product::where('stock', '<', 10)->get();

        return view('dashboard', compact('data'));
    }
}
