@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adicionar Produto</h1>
    <form action="{{ route('products.store') }}" method="POST" id="product-form">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Código (EAN/UPC)</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" autocomplete="off">
            <small class="form-text text-muted">Digite o código de barras para buscar informações automaticamente.</small>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="cost_price" class="form-label">Preço de Custo</label>
            <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required>
        </div>
        <div class="mb-3">
            <label for="profit_margin" class="form-label">Margem de Lucro (%)</label>
            <input type="number" step="0.01" class="form-control" id="profit_margin" name="profit_margin" value="{{ old('profit_margin') }}" required>
        </div>
        <div class="mb-3">
            <label for="sale_price" class="form-label">Preço de Venda (calculado automaticamente)</label>
            <input type="text" class="form-control" id="sale_price" value="" disabled>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Estoque</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script>
document.getElementById('code').addEventListener('blur', function() {
    const code = this.value.trim();
    if (!code) return;

    // Limpa campos antes da busca
    document.getElementById('name').value = '';

    // Exemplo de busca na API Open Food Facts
    fetch(`https://world.openfoodfacts.org/api/v0/product/${code}.json`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 1) {
                const product = data.product;
                // Preenche o nome do produto
                if (product.product_name) {
                    document.getElementById('name').value = product.product_name;
                }
            } else {
                alert('Produto não encontrado na base externa.');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar produto:', error);
            alert('Erro ao buscar informações do produto.');
        });
});

// Calcula preço de venda automaticamente quando custo ou margem mudam
function calculateSalePrice() {
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const profitMargin = parseFloat(document.getElementById('profit_margin').value) || 0;
    const salePrice = costPrice * (1 + profitMargin / 100);
    document.getElementById('sale_price').value = salePrice.toFixed(2);
}

document.getElementById('cost_price').addEventListener('input', calculateSalePrice);
document.getElementById('profit_margin').addEventListener('input', calculateSalePrice);

// Calcula inicial se valores existirem
calculateSalePrice();
</script>
@endsection