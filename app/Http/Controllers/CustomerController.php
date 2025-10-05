<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerHistory;
use App\Models\Sale;
use App\Models\AccountsReceivable;

class CustomerController extends Controller
{
    /**
     * Exibe a lista de clientes com paginação.
     */
    public function index()
    {
        $customers = Customer::orderBy('name')->paginate(10); // 10 clientes por página
        return view('customers.index', compact('customers'));
    }

    /**
     * Formulário de criação de cliente.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Armazena um novo cliente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'contact' => 'required|string|max:255',
            'rg' => 'required|string|max:255',
            'cpf' => 'required|string|unique:customers',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('customers', 'public') : null;

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'rg' => $request->rg,
            'cpf' => $request->cpf,
            'birthdate' => $request->birthdate,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        // Log history
        CustomerHistory::create([
            'customer_id' => $customer->id,
            'action' => 'created',
            'description' => 'Customer created',
            'created_at' => now(),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Exibe um cliente específico.
     */
    public function show(Customer $customer)
    {
        $histories = $customer->recentHistories();
        $pendingSales = Sale::where('customer_id', $customer->id)->where('status', 'pending')->get();
        $receivables = AccountsReceivable::where('customer_id', $customer->id)->where('status', 'pending')->get();
        $orders = $customer->orders()->with('sale')->get(); // Include orders with related sale
        return view('customers.show', compact('customer', 'histories', 'pendingSales', 'receivables', 'orders'));
    }

    /**
     * Formulário de edição de cliente.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Atualiza os dados de um cliente.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'contact' => 'required|string|max:255',
            'rg' => 'required|string|max:255',
            'cpf' => 'required|string|unique:customers,cpf,' . $customer->id,
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $customer->photo;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('customers', 'public');
        }

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'rg' => $request->rg,
            'cpf' => $request->cpf,
            'birthdate' => $request->birthdate,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        // Log history
        CustomerHistory::create([
            'customer_id' => $customer->id,
            'action' => 'updated',
            'description' => 'Customer updated',
            'created_at' => now(),
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove um cliente.
     */
    public function destroy(Customer $customer)
    {
        // Log history before deleting
        CustomerHistory::create([
            'customer_id' => $customer->id,
            'action' => 'deleted',
            'description' => 'Customer deleted',
            'created_at' => now(),
        ]);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * Formulário para editar apenas o contato do cliente.
     */
    public function editContact(Customer $customer)
    {
        return view('customers.edit-contact', compact('customer'));
    }

    /**
     * Atualiza apenas o contato do cliente.
     */
    public function updateContact(Request $request, Customer $customer)
    {
        $request->validate([
            'contact' => 'required|string|max:255',
        ]);

        $customer->update(['contact' => $request->contact]);

        // Log history
        CustomerHistory::create([
            'customer_id' => $customer->id,
            'action' => 'contact_updated',
            'description' => 'Customer contact updated',
            'created_at' => now(),
        ]);

        return redirect()->route('customers.index')->with('success', 'Contact updated successfully.');
    }
}
