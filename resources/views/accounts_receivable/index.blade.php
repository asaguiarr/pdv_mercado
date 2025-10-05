@extends('layouts.admin')

@section('title', 'Contas a Receber')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12"></div></div>
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
            @foreach ($receivables as $receivable)
            <tr>
                <td>{{ $receivable->id }}</td>
                <td>{{ $receivable->customer->name ?? 'N/A' }}</td>
                <td>{{ $receivable->order ? $receivable->order->id : 'N/A' }}</td>
                <td>R$ {{ number_format($receivable->amount, 2, ',', '.') }}</td>
                <td>{{ $receivable->due_date->format('d/m/Y') }}</td>
                <td>{{ $receivable->paid_date ? $receivable->paid_date->format('d/m/Y') : '-' }}</td>
                <td>{{ ucfirst($receivable->status) }}</td>
                <td>
                    <a href="{{ route('accounts_receivable.edit', $receivable->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('accounts_receivable.destroy', $receivable->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $receivables->links() }}
</div>
@endsection
