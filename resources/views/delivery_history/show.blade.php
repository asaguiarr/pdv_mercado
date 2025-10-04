@extends('layouts.app')

@section('title', 'Detalhes do Histórico de Entrega')

@section('content')
<div class="container py-4">
    <h1>Detalhes do Histórico de Entrega</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Entregador:</strong> {{ $deliveryHistory->deliveryPerson->name ?? 'N/A' }}</p>
            <p><strong>Pedido:</strong> {{ $deliveryHistory->order->id ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $deliveryHistory->status }}</p>
            <p><strong>Notas:</strong> {{ $deliveryHistory->notes }}</p>
            <p><strong>Data:</strong> {{ $deliveryHistory->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('delivery_history.index') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
