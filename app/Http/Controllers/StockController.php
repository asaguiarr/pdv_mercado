<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;

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

    public function invoiceEntrada()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('estoque.invoice_entrada', compact('products', 'suppliers'));
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

    public function storeInvoiceEntrada(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'nullable|numeric|min:0',
            'products.*.new_product' => 'nullable|boolean',
            'products.*.name' => 'required_if:products.*.new_product,true|string|max:255',
            'products.*.description' => 'nullable|string',
        ]);

        $invoiceNumber = $request->invoice_number;
        $supplierId = $request->supplier_id;
        $purchaseDate = $request->purchase_date;

        foreach ($request->products as $item) {
            $productId = $item['product_id'];

            if (isset($item['new_product']) && $item['new_product']) {
                // Create new product
                $product = Product::create([
                    'name' => $item['name'],
                    'description' => $item['description'] ?? '',
                    'price' => $item['unit_price'] ?? 0,
                    'stock' => 0, // Will be incremented below
                ]);
                $productId = $product->id;
            }

            $product = Product::find($productId);
            $product->increment('stock', $item['quantity']);

            // Update price if provided
            if (isset($item['unit_price']) && $item['unit_price'] > 0) {
                $product->update(['price' => $item['unit_price']]);
            }

            // Record stock movement
            StockMovement::create([
                'product_id' => $productId,
                'type' => 'in',
                'quantity' => $item['quantity'],
                'reference_type' => 'invoice',
                'notes' => 'Invoice entry',
                'user_id' => Auth::id(),
                'invoice_number' => $invoiceNumber,
                'supplier_id' => $supplierId,
                'purchase_date' => $purchaseDate,
            ]);
        }

        return redirect()->route('estoque.index')->with('success', 'Invoice stock entry recorded successfully.');
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
