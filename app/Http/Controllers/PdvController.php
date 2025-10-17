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
use App\Models\CashMovement;
use App\Models\CustomerHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdvController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $currentSale = Sale::where('user_id', Auth::id())->where('status', 'open')->first();

        $query = Sale::where('user_id', Auth::id());

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }

        $sales = $query->paginate(10);

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
            'payment_method' => 'required|in:dinheiro,debito,credito,pix,prazo',
            'delivery_type' => 'required|in:retirada,entrega',
        ]);

        // Require customer for delivery or credit sales
        if (($request->delivery_type === 'entrega' || $request->payment_method === 'prazo') && !$request->customer_id) {
            return response()->json(['error' => 'Cliente é obrigatório para entregas ou vendas a prazo.'], 422);
        }

        // Check if cash is open only for cash payments
        $cashStatus = null;
        if ($request->payment_method === 'dinheiro') {
            $cashStatus = CashStatus::where('user_id', Auth::id())->where('status', 'open')->first();
            if (!$cashStatus) {
                return response()->json(['error' => 'Caixa não está aberto. Abra o caixa antes de realizar vendas em dinheiro.'], 422);
            }
        }

        $sale = DB::transaction(function () use ($request, $cashStatus) {
            $total = 0;
            foreach ($request->cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $total -= $request->discount ?? 0;

            // Determine sale status based on delivery type
            $status = $request->delivery_type === 'entrega' ? 'pending' : 'closed';

            // Determine payment status based on payment method
            $paymentMethods = [
                'dinheiro' => 'paid',
                'debito' => 'paid',
                'credito' => 'paid',
                'pix' => 'paid',
                'prazo' => 'pending', // Corrigido: vendas a prazo são pendentes
            ];
            $paymentStatus = $paymentMethods[$request->payment_method] ?? 'pending';

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'total' => $total,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'discount' => $request->discount ?? 0,
                'delivery_type' => $request->delivery_type,
            ]);

            // Create order for delivery
            $order = null;
            if ($request->delivery_type === 'entrega') {
                $customer = Customer::find($request->customer_id);
                $order = Order::create([
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer ? $customer->name : 'Cliente',
                    'items' => $request->cart,
                    'status' => 'todo',
                    'sale_id' => $sale->id,
                ]);

                // Record in customer history
                CustomerHistory::create([
                    'customer_id' => $request->customer_id,
                    'action' => 'sale',
                    'description' => 'Pedido de entrega criado - Venda ID: ' . $sale->id,
                ]);
            }

            // Create accounts receivable for pending payments
            if ($paymentStatus === 'pending' && $request->customer_id) {
                $dueDate = now()->addDays(30);
                \App\Models\AccountsReceivable::create([
                    'customer_id' => $request->customer_id,
                    'order_id' => $order ? $order->id : null,
                    'amount' => $total,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);

                // Update customer balance
                $customer = Customer::find($request->customer_id);
                if ($customer) {
                    $customer->updateBalance();
                }
            }

            // Record cash movement only for cash payments
            if ($paymentStatus === 'paid' && $request->payment_method === 'dinheiro' && $cashStatus) {
                CashMovement::create([
                    'cash_status_id' => $cashStatus->id,
                    'type' => 'entry',
                    'amount' => $total,
                    'description' => 'Venda ID: ' . $sale->id,
                    'sale_id' => $sale->id,
                    'user_id' => Auth::id(),
                ]);
            }

            // Process stock movements for each cart item - only for immediate sales
            if ($status === 'closed') {
                foreach ($request->cart as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception('Estoque insuficiente para o produto: ' . $product->name);
                    }
                    $product->decrement('stock', $item['quantity']);

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ]);

                    // Record stock movement
                    StockMovement::create([
                        'product_id' => $item['product_id'],
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'reference_type' => 'sale',
                        'notes' => 'Venda processada',
                        'user_id' => Auth::id(),
                    ]);
                }
            } else {
                // For pending sales (delivery), create sale items without decrementing stock
                foreach ($request->cart as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ]);
                }
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
        $products = Product::where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
            ->limit(10)
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

        $product->decrement('stock', $request->quantity);

        // Record stock movement
        StockMovement::create([
            'product_id' => $request->product_id,
            'type' => 'out',
            'quantity' => $request->quantity,
            'reference_type' => 'cart_add',
            'notes' => 'Added to cart',
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function removeItem(Request $request)
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
            'reference_type' => 'cart_remove',
            'notes' => 'Removed from cart',
            'user_id' => Auth::id(),
        ]);

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
        if (!$cashStatus) {
            return redirect()->route('pdv.report')->withErrors('No open cash to close.');
        }

        // Calculate final balance from cash movements
        $totalEntries = CashMovement::where('cash_status_id', $cashStatus->id)
            ->where('type', 'entry')
            ->sum('amount');

        $totalExits = CashMovement::where('cash_status_id', $cashStatus->id)
            ->where('type', 'exit')
            ->sum('amount');

        $finalBalance = $cashStatus->initial_balance + $totalEntries - $totalExits;

        $cashStatus->update([
            'status' => 'closed',
            'final_balance' => $finalBalance,
            'closed_at' => now(),
        ]);

        return redirect()->route('pdv.report')->with('success', 'Cash closed successfully. Final balance: ' . number_format($finalBalance, 2));
    }

    public function report()
    {
        $sales = Sale::where('user_id', Auth::id())->where('status', 'closed')->get();
        return view('pdv.report', compact('sales'));
    }
}
