<?php

namespace App\Http\Controllers;

use App\Models\AccountsReceivable;
use Illuminate\Http\Request;

class AccountsReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountsReceivable = AccountsReceivable::with(['customer', 'order'])->paginate(15);
        return view('accounts_receivable.index', compact('accountsReceivable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts_receivable.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
            'status' => 'required|in:pending,paid',
        ]);

        AccountsReceivable::create($validated);

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a Receber criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountsReceivable $accountsReceivable)
    {
        $accountsReceivable->load(['customer', 'order']);
        return view('accounts_receivable.show', compact('accountsReceivable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountsReceivable $accountsReceivable)
    {
        return view('accounts_receivable.edit', compact('accountsReceivable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountsReceivable $accountsReceivable)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
            'status' => 'required|in:pending,paid',
        ]);

        $accountsReceivable->update($validated);

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a Receber atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountsReceivable $accountsReceivable)
    {
        $accountsReceivable->delete();

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a Receber excluída com sucesso.');
    }
}
