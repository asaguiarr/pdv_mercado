@extends('layouts.app')

@section('title', 'Adicionar Produto')

@section('content')
<div class="container mt-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-primary mb-0">
            <i class="fas fa-box-open me-2"></i>Adicionar Produto
        </h1>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <!-- Card do formulário -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" id="product-form">
                @csrf

                <div class="row g-3">
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="code" class="form-label">Código (EAN/UPC)</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" class="form-control" placeholder="Ex: 7891234567890">
                        <div class="form-text">Digite o código de barras para buscar informações automaticamente.</div>
                    </div>

                    <!-- Nome -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required placeholder="Nome do produto">
                    </div>

                    <!-- Preço de Custo -->
                    <div class="col-md-4">
                        <label for="cost_price" class="form-label">Preço de Custo</label>
                        <input type="number" step="0.01" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" class="form-control" required placeholder="0.00">
                    </div>

                    <!-- Margem de Lucro -->
                    <div class="col-md-4">
                        <label for="profit_margin" class="form-label">Margem de Lucro (%)</label>
                        <input type="number" step="0.01" id="profit_margin" name="profit_margin" value="{{ old('profit_margin') }}" class="form-control" required placeholder="Ex: 20">
                    </div>

                    <!-- Preço de Venda -->
                    <div class="col-md-4">
                        <label for="sale_price" class="form-label">Preço de Venda</label>
                        <input type="text" id="sale_price" class="form-control bg-light" disabled placeholder="Calculado automaticamente">
                    </div>

                    <!-- Estoque -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Estoque</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}" class="form-control" required placeholder="Quantidade em estoque">
                    </div>
                </div>

                <!-- Botão -->
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script -->
<script>
document.getElementById('code').addEventListener('blur', async function () {
    const code = this.value.trim();
    if (!code) return;

    document.getElementById('name').value = '';

    try {
        const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${code}.json`);
        const data = await response.json();
        if (data.status === 1 && data.product?.product_name) {
            document.getElementById('name').value = data.product.product_name;
        } else {
            alert('Produto não encontrado na base externa.');
        }
    } catch (error) {
        console.error('Erro ao buscar produto:', error);
        alert('Erro ao buscar informações do produto.');
    }
});

// Calcula preço de venda automaticamente
function calculateSalePrice() {
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const profitMargin = parseFloat(document.getElementById('profit_margin').value) || 0;
    const salePrice = costPrice * (1 + profitMargin / 100);
    document.getElementById('sale_price').value = salePrice.toFixed(2);
}

document.getElementById('cost_price').addEventListener('input', calculateSalePrice);
document.getElementById('profit_margin').addEventListener('input', calculateSalePrice);
calculateSalePrice();
</script>
@endsection
