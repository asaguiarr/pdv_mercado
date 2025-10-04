@extends('layouts.app')

@section('title', 'Editar Conta a Receber')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Editar Conta a Receber</h1>

    <form action="{{ route('accounts_receivable.update', $account->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Cliente</label>
                    <select name="customer_id" id="customer_id" class="form-control" required>
                        <option value="">Selecione um cliente</option>
                        @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $account->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="order_id" class="form-label">Pedido (Opcional)</label>
                    <select name="order_id" id="order_id" class="form-control">
                        <option value="">Selecione um pedido</option>
                        @foreach ($orders as $order)
                        <option value="{{ $order->id }}" {{ $account->order_id == $order->id ? 'selected' : '' }}>Pedido #{{ $order->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="amount" class="form-label">Valor</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $account->amount }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="due_date" class="form-label">Data de Vencimento</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $account->due_date->format('Y-m-d') }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $account->status == 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="paid" {{ $account->status == 'paid' ? 'selected' : '' }}>Pago</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="paid_date" class="form-label">Data de Pagamento (se pago)</label>
                    <input type="date" name="paid_date" id="paid_date" class="form-control" value="{{ $account->paid_date ? $account->paid_date->format('Y-m-d') : '' }}">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('accounts_receivable.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
