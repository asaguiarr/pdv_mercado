<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountsReceivable;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AccountsReceivableController extends Controller
{
    public function index()
    {
        $receivables = AccountsReceivable::with('customer')->paginate(10);
        return view('accounts_receivable.index', compact('receivables'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('accounts_receivable.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        AccountsReceivable::create([
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a receber criada com sucesso.');
    }

    public function show($id)
    {
        $receivable = AccountsReceivable::with('customer')->findOrFail($id);
        return view('accounts_receivable.show', compact('receivable'));
    }

    public function edit($id)
    {
        $receivable = AccountsReceivable::findOrFail($id);
        $customers = Customer::all();
        return view('accounts_receivable.edit', compact('receivable', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        $receivable = AccountsReceivable::findOrFail($id);
        $receivable->update($request->all());

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a receber atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $receivable = AccountsReceivable::findOrFail($id);
        $receivable->delete();

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a receber excluída com sucesso.');
    }

    public function markAsPaid($id)
    {
        $receivable = AccountsReceivable::findOrFail($id);
        $receivable->update(['status' => 'paid']);

        return redirect()->route('accounts_receivable.index')->with('success', 'Conta a receber marcada como paga.');
    }
}
