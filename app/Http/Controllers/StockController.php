<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('estoque.index', compact('products'));
    }

    public function entrada()
    {
        $products = Product::all();
        return view('estoque.entrada', compact('products'));
    }

    public function storeEntrada(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $product->increment('stock', $request->quantity);

        // Record stock movement
        StockMovement::create([
            'product_id' => $request->product_id,
            'type' => 'in',
            'quantity' => $request->quantity,
            'reference_type' => 'manual',
            'notes' => 'Manual stock entry',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('estoque.index')->with('success', 'Stock entry recorded successfully.');
    }

    public function saida()
    {
        $products = Product::all();
        return view('estoque.saida', compact('products'));
    }

    public function storeSaida(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        if ($product->stock >= $request->quantity) {
            $product->decrement('stock', $request->quantity);

            // Record stock movement
            StockMovement::create([
                'product_id' => $request->product_id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'reference_type' => 'manual',
                'notes' => 'Manual stock exit',
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('estoque.index')->with('success', 'Stock exit recorded successfully.');
        } else {
            return back()->withErrors(['quantity' => 'Insufficient stock.']);
        }
    }

    public function relatorio()
    {
        $products = Product::all();
        return view('estoque.relatorio', compact('products'));
    }
}
