<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:products,code',
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0|max:100',
            'stock' => 'required|numeric|min:0',
        ]);

        Product::create($request->only([
            'code',
            'name',
            'cost_price',
            'profit_margin',
            'stock',
        ]));

        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0|max:100',
            'stock' => 'required|numeric|min:0',
        ]);

        $product->update($request->only([
            'code',
            'name',
            'cost_price',
            'profit_margin',
            'stock',
        ]));

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso!');
    }
}
