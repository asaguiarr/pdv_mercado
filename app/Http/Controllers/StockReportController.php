<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function index()
    {
        $stockReport = Product::select(
            'products.id',
            'products.name',
            'products.stock as current_stock',
            DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "in" THEN stock_movements.quantity ELSE 0 END), 0) as total_in'),
            DB::raw('COALESCE(SUM(CASE WHEN stock_movements.type = "out" THEN stock_movements.quantity ELSE 0 END), 0) as total_out'),
            DB::raw('MAX(stock_movements.created_at) as last_movement_date')
        )
        ->leftJoin('stock_movements', 'products.id', '=', 'stock_movements.product_id')
        ->groupBy('products.id', 'products.name', 'products.stock')
        ->orderBy('products.name')
        ->get();

        return view('stock.report', compact('stockReport'));
    }
}
