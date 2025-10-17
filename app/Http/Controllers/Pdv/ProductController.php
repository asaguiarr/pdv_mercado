<?php

namespace App\Http\Controllers\Pdv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Busca por q (nome/código/barcode) ou retorna todos se q vazio
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Product::query();

        if ($q) {
            $query->where(function($qbuilder) use ($q) {
                $qbuilder->where('name', 'like', "%{$q}%")
                         ->orWhere('code', $q)
                         ->orWhere('barcode', $q);
            });
        }

        $products = $query->limit(50)->get()->map(function($p){
            // tenta vários nomes de campo de estoque (stock, quantity, qty, estoque)
            $stock = $p->stock ?? $p->quantity ?? $p->qty ?? $p->estoque ?? 0;
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => (float) ($p->price ?? $p->sale_price ?? 0),
                'stock' => (int) $stock,
                'code' => $p->code ?? null,
                'barcode' => $p->barcode ?? null,
            ];
        });

        return response()->json($products);
    }

    // Retorna um produto por id
    public function show(Request $request, $id)
    {
        $p = Product::findOrFail($id);
        $stock = $p->stock ?? $p->quantity ?? $p->qty ?? $p->estoque ?? 0;

        return response()->json([
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) ($p->price ?? $p->sale_price ?? 0),
            'stock' => (int) $stock,
            'code' => $p->code ?? null,
            'barcode' => $p->barcode ?? null,
        ]);
    }
}
