@extends('layouts.app')

@section('title', 'Detalhes do Produto')

@section('content')
<div class="container py-4">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary mb-0">
            <i class="fas fa-box-open me-2"></i>Detalhes do Produto
        </h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-semibold">{{ $product->name }}</h5>
            <ul class="list-group list-group-flush mt-3">
                <li class="list-group-item"><strong>Código:</strong> {{ $product->code ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Preço de Custo:</strong> R$ {{ number_format($product->cost_price, 2, ',', '.') }}</li>
                <li class="list-group-item"><strong>Margem de Lucro:</strong> {{ $product->profit_margin }}%</li>
                <li class="list-group-item"><strong>Preço de Venda:</strong> R$ {{ number_format($product->sale_price, 2, ',', '.') }}</li>
                <li class="list-group-item"><strong>Estoque:</strong> {{ $product->stock }}</li>
            </ul>
        </div>
    </div>

</div>
@endsection
