<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;

class SaleController extends Controller
{
    public function create()
    {
        $customersQuery = Customer::orderBy('name');
        if (Schema::hasColumn('customers', 'active')) {
            $customersQuery->where('active', true);
        }
        $customers = $customersQuery->get();

        return view('pdv.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_cpf' => 'nullable|string|max:14|unique:customers,cpf',
            'customer_contact' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:dinheiro,pix,debito,credito',
            'discount' => 'nullable|numeric|min:0',
            'delivery_type' => 'required|string|in:retirada,entrega',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request) {
            $customer = null;
            if ($request->filled('customer_id')) {
                $customer = Customer::find($request->customer_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'cpf' => $request->customer_cpf,
                    'contact' => $request->customer_contact,
                    'active' => true,
                ]);
            }

            $productsData = $request->products;
            $saleItems = [];
            $total = 0;

            foreach ($productsData as $productData) {
                $product = Product::find($productData['id']);

                if ($product->stock < $productData['quantity']) {
                    throw new \Exception("Estoque insuficiente para '{$product->name}'. Disponível: {$product->stock}, Solicitado: {$productData['quantity']}.");
                }

                $itemTotal = $productData['quantity'] * $productData['unit_price'];
                $total += $itemTotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'total_price' => $itemTotal,
                ];
            }

            $discount = $request->discount ?? 0;
            $total -= $discount;

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'total' => $total,
                'status' => 'closed',
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'discount' => $discount,
                'delivery_type' => $request->delivery_type,
                'closed_at' => now(),
            ]);

            foreach ($saleItems as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);

                $product = Product::find($itemData['product_id']);
                $decremented = Product::where('id', $product->id)
                    ->where('stock', '>=', $itemData['quantity'])
                    ->decrement('stock', $itemData['quantity']);

                if ($decremented === 0) {
                    throw new \Exception("Estoque insuficiente para '{$product->name}' devido a concorrência.");
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'reference_type' => 'sale',
                    'notes' => 'Saída por venda',
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('pdv.sales')->with('success', 'Venda registrada e estoque atualizado com sucesso!');
    }

    public function createSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer' => 'required|array',
            'customer.cpf' => 'nullable|string',
            'customer.email' => 'nullable|email',
            'customer.name' => 'required_with:customer.cpf,customer.email|string',
            'customer.contact' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'nullable|exists:products,id',
            'products.*.name' => 'required_without:products.*.id|string',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'delivery_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Dados inválidos.', 'errors' => $validator->errors()], 422);
        }

        $sale = DB::transaction(function () use ($request) {
            $customerData = $request->input('customer');
            $customer = null;

            if (isset($customerData['cpf'])) {
                $customer = Customer::where('cpf', $customerData['cpf'])->first();
            } elseif (isset($customerData['email'])) {
                $customer = Customer::where('email', $customerData['email'])->first();
            }

            if (!$customer) {
                $customer = Customer::create([
                    'name' => $customerData['name'],
                    'email' => $customerData['email'] ?? null,
                    'contact' => $customerData['contact'] ?? null,
                    'cpf' => $customerData['cpf'] ?? null,
                    'rg' => $customerData['rg'] ?? null,
                    'birthdate' => $customerData['birthdate'] ?? null,
                    'address' => $customerData['address'] ?? null,
                ]);
            }

            $productsData = $request->input('products');
            $saleItems = [];
            $total = 0;

            foreach ($productsData as $productData) {
                $product = null;

                if (isset($productData['id'])) {
                    $product = Product::find($productData['id']);
                } else {
                    $product = Product::create([
                        'name' => $productData['name'],
                        'sale_price' => $productData['unit_price'],
                        'stock' => 0,
                        'active' => true,
                    ]);
                }

                if ($product->stock < $productData['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto '{$product->name}'. Disponível: {$product->stock}, Solicitado: {$productData['quantity']}.");
                }

                $itemTotal = $productData['quantity'] * $productData['unit_price'];
                $total += $itemTotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'total_price' => $itemTotal,
                ];
            }

            $discount = $request->input('discount', 0);
            $total -= $discount;

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'total' => $total,
                'status' => 'closed',
                'payment_status' => 'paid',
                'payment_method' => $request->input('payment_method'),
                'discount' => $discount,
                'delivery_type' => $request->input('delivery_type'),
                'closed_at' => now(),
            ]);

            foreach ($saleItems as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);

                $product = Product::find($itemData['product_id']);
                $decremented = Product::where('id', $product->id)
                    ->where('stock', '>=', $itemData['quantity'])
                    ->decrement('stock', $itemData['quantity']);

                if ($decremented === 0) {
                    throw new \Exception("Estoque insuficiente para '{$product->name}' devido a concorrência.");
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'reference_type' => 'sale',
                    'notes' => 'Saída por venda',
                    'user_id' => auth()->id(),
                ]);
            }

            return $sale;
        });

        return response()->json(['success' => true, 'message' => 'Venda registrada e estoque atualizado com sucesso!', 'sale' => $sale]);
    }
}
