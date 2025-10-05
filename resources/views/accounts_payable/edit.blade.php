@extends('layouts.app')

@section('title', 'Editar Conta a Pagar')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Editar Conta a Pagar</h1>

    <form action="{{ route('accounts_payable.update', $accountsPayable->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Fornecedor</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                <option value="">Selecione um fornecedor</option>
                @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $accountsPayable->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Valor</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $accountsPayable->amount }}" required>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Data de Vencimento</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $accountsPayable->due_date->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $accountsPayable->status == 'pending' ? 'selected' : '' }}>Pendente</option>
                <option value="paid" {{ $accountsPayable->status == 'paid' ? 'selected' : '' }}>Pago</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="paid_date" class="form-label">Data de Pagamento (se pago)</label>
            <input type="date" name="paid_date" id="paid_date" class="form-control" value="{{ $accountsPayable->paid_date ? $accountsPayable->paid_date->format('Y-m-d') : '' }}">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('accounts_payable.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
