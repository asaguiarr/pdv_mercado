@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Produto</h1>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="code" class="form-label">Código</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $product->code) }}">
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="cost_price" class="form-label">Preço de Custo</label>
            <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required>
        </div>
        <div class="mb-3">
            <label for="profit_margin" class="form-label">Margem de Lucro (%)</label>
            <input type="number" step="0.01" class="form-control" id="profit_margin" name="profit_margin" value="{{ old('profit_margin', $product->profit_margin) }}" required>
        </div>
        <div class="mb-3">
            <label for="sale_price" class="form-label">Preço de Venda (calculado automaticamente)</label>
            <input type="text" class="form-control" id="sale_price" value="{{ $product->sale_price ?? '' }}" disabled>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Estoque</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

<script>
// Calcula preço de venda automaticamente quando custo ou margem mudam
function calculateSalePrice() {
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const profitMargin = parseFloat(document.getElementById('profit_margin').value) || 0;
    const salePrice = costPrice * (1 + profitMargin / 100);
    document.getElementById('sale_price').value = salePrice.toFixed(2);
}

document.getElementById('cost_price').addEventListener('input', calculateSalePrice);
document.getElementById('profit_margin').addEventListener('input', calculateSalePrice);

// Calcula inicial
calculateSalePrice();
</script>
@endsection
