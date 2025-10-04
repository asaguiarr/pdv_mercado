@extends('layouts.app')

@section('title', 'Contas a Receber')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Contas a Receber</h1>

    <a href="{{ route('accounts_receivable.create') }}" class="btn btn-primary mb-3">Nova Conta a Receber</a>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Pedido</th>
                <th>Valor</th>
                <th>Data de Vencimento</th>
                <th>Data de Pagamento</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accountsReceivable as $account)
            <tr>
                <td>{{ $account->id }}</td>
                <td>{{ $account->customer->name ?? 'N/A' }}</td>
                <td>{{ $account->order->id ?? 'N/A' }}</td>
                <td>R$ {{ number_format($account->amount, 2, ',', '.') }}</td>
                <td>{{ $account->due_date->format('d/m/Y') }}</td>
                <td>{{ $account->paid_date ? $account->paid_date->format('d/m/Y') : '-' }}</td>
                <td>{{ ucfirst($account->status) }}</td>
                <td>
                    <a href="{{ route('accounts_receivable.edit', $account->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('accounts_receivable.destroy', $account->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $accountsReceivable->links() }}
</div>
@endsection
