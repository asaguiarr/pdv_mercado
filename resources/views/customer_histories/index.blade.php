@extends('layouts.app')

@section('title', 'Histórico do Cliente')

@section('content')
<div class="container py-4">
    <h1>Histórico do Cliente: {{ $customer->name }}</h1>

    <a href="{{ route('customers.index') }}" class="btn btn-secondary mb-3">Voltar para Clientes</a>

    @if($histories->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Ação</th>
                    <th>Descrição</th>
                    <th>Usuário Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($history->action) }}</td>
                        <td>{{ $history->description }}</td>
                        <td>{{ $history->user->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('customer_histories.show', [$customer->id, $history->id]) }}" class="btn btn-primary btn-sm">Detalhes</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $histories->links() }}
    @else
        <p>Nenhum histórico encontrado para este cliente.</p>
    @endif
</div>
@endsection
