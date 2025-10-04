@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes do Produto</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-text"><strong>Código:</strong> {{ $product->code }}</p>
            <p class="card-text"><strong>Preço de Custo:</strong> R$ {{ number_format($product->cost_price, 2, ',', '.') }}</p>
            <p class="card-text"><strong>Margem de Lucro:</strong> {{ $product->profit_margin }}%</p>
            <p class="card-text"><strong>Preço de Venda:</strong> R$ {{ number_format($product->sale_price, 2, ',', '.') }}</p>
            <p class="card-text"><strong>Estoque:</strong> {{ $product->stock }}</p>
        </div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
