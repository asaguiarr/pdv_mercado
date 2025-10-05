<?php

namespace App\Http\Controllers;

use App\Models\AccountsPayable;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AccountsPayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountsPayable = AccountsPayable::with('supplier')->paginate(15);
        return view('accounts_payable.index', compact('accountsPayable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('accounts_payable.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
            'status' => 'required|in:pending,paid',
        ]);

        AccountsPayable::create($validated);

        return redirect()->route('accounts_payable.index')->with('success', 'Conta a Pagar criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountsPayable $accountsPayable)
    {
        $accountsPayable->load('supplier');
        return view('accounts_payable.show', compact('accountsPayable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountsPayable $accountsPayable)
    {
        $suppliers = Supplier::all();
        return view('accounts_payable.edit', compact('accountsPayable', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountsPayable $accountsPayable)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
            'status' => 'required|in:pending,paid',
        ]);

        $accountsPayable->update($validated);

        return redirect()->route('accounts_payable.index')->with('success', 'Conta a Pagar atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountsPayable $accountsPayable)
    {
        $accountsPayable->delete();

        return redirect()->route('accounts_payable.index')->with('success', 'Conta a Pagar excluída com sucesso.');
    }
}
