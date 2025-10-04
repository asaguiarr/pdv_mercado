@extends('layouts.app')

@section('title', 'Histórico de Entregas')

@section('content')
<div class="container py-4">
    <h1>Histórico de Entregas</h1>

    @if($histories->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Entregador</th>
                    <th>Pedido</th>
                    <th>Status</th>
                    <th>Notas</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->deliveryPerson->name ?? 'N/A' }}</td>
                        <td>{{ $history->order->id ?? 'N/A' }}</td>
                        <td>{{ $history->status }}</td>
                        <td>{{ $history->notes }}</td>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $histories->links() }}
    @else
        <p>Nenhum histórico de entregas disponível.</p>
    @endif
</div>
@endsection
