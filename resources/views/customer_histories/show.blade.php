@extends('layouts.app')

@section('title', 'Detalhes do Histórico do Cliente')

@section('content')
<div class="container py-4">
    <h1>Detalhes do Histórico</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $customer->name }}</p>
            <p><strong>Data:</strong> {{ $history->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Ação:</strong> {{ ucfirst($history->action) }}</p>
            <p><strong>Descrição:</strong> {{ $history->description }}</p>
            <p><strong>Usuário Responsável:</strong> {{ $history->user->name ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('customer_histories.index', $customer->id) }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
