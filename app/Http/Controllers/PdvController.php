<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CashStatus;
use App\Models\Customer;
use App\Models\StockMovement;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdvController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $currentSale = Sale::where('user_id', Auth::id())->where('status', 'open')->first();
        $sales = Sale::where('user_id', Auth::id())->get();
        return view('pdv.index', compact('products', 'currentSale', 'sales'));
    }

    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('pdv.create', compact('products', 'customers'));
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|string',
        ]);

        $sale = DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $total -= $request->discount ?? 0;

            $status = $request->delivery_type == 'entrega' ? 'pending' : 'closed';

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'total' => $total,
                'status' => $status,
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'discount' => $request->discount ?? 0,
                'delivery_type' => $request->delivery_type,
            ]);

            if ($request->delivery_type == 'entrega') {
                $customer = Customer::find($request->customer_id);
                Order::create([
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer ? $customer->name : 'Cliente',
                    'items' => $request->cart,
                    'status' => 'todo',
                ]);
            }

            foreach ($request->cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Insufficient stock for product: ' . $product->name);
                }
                $product->decrement('stock', $item['quantity']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Record stock movement for sale
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'sale',
                    'notes' => 'Sale processed',
                    'user_id' => Auth::id(),
                ]);
            }

            return $sale;
        });

        return response()->json(['sale' => $sale]);
    }

    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        return view('pdv.show', compact('sale'));
    }

    public function getProduct(Request $request)
    {
        $search = $request->query('search');
        if (is_numeric($search)) {
            $product = Product::where('code', $search)->first();
            if ($product) {
                return response()->json([$product]);
            }
        }
        $products = Product::where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
            ->get();
        return response()->json($products);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::lockForUpdate()->find($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 422);
        }

        DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->find($request->product_id);
            $product->decrement('stock', $request->quantity);

            StockMovement::create([
                'product_id' => $request->product_id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'reference_type' => 'cart_add',
                'notes' => 'Added to cart',
                'user_id' => Auth::id(),
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function removeItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->find($request->product_id);
            $product->increment('stock', $request->quantity);

            StockMovement::create([
                'product_id' => $request->product_id,
                'type' => 'in',
                'quantity' => $request->quantity,
                'reference_type' => 'cart_remove',
                'notes' => 'Removed from cart',
                'user_id' => Auth::id(),
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function openCash()
    {
        return view('pdv.open-cash');
    }

    public function openCashStore(Request $request)
    {
        $request->validate([
            'initial_balance' => 'required|numeric|min:0',
        ]);

        CashStatus::create([
            'user_id' => Auth::id(),
            'initial_balance' => $request->initial_balance,
            'status' => 'open',
        ]);

        return redirect()->route('pdv.sales')->with('success', 'Cash opened successfully.');
    }

    public function closeCash()
    {
        $cashStatus = CashStatus::where('user_id', Auth::id())->where('status', 'open')->first();
        return view('pdv.close-cash', compact('cashStatus'));
    }

    public function closeCashStore(Request $request)
    {
        $cashStatus = CashStatus::where('user_id', Auth::id())->where('status', 'open')->first();
        $cashStatus->update(['status' => 'closed']);

        return redirect()->route('pdv.report')->with('success', 'Cash closed successfully.');
    }

    public function report()
    {
        $sales = Sale::where('user_id', Auth::id())->where('status', 'closed')->get();
        return view('pdv.report', compact('sales'));
    }
}
