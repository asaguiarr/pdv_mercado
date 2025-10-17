@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="container mt-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-primary mb-0">
            <i class="fas fa-pen-to-square me-2"></i>Editar Produto
        </h1>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <!-- Card do formulário -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST" id="product-form">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="code" class="form-label">Código (EAN/UPC)</label>
                        <input type="text" id="code" name="code"
                            value="{{ old('code', $product->code) }}"
                            class="form-control" placeholder="Ex: 7891234567890">
                    </div>

                    <!-- Nome -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $product->name) }}"
                            class="form-control" required placeholder="Nome do produto">
                    </div>

                    <!-- Preço de Custo -->
                    <div class="col-md-4">
                        <label for="cost_price" class="form-label">Preço de Custo</label>
                        <input type="number" step="0.01" id="cost_price" name="cost_price"
                            value="{{ old('cost_price', $product->cost_price) }}"
                            class="form-control" required placeholder="0.00">
                    </div>

                    <!-- Margem de Lucro -->
                    <div class="col-md-4">
                        <label for="profit_margin" class="form-label">Margem de Lucro (%)</label>
                        <input type="number" step="0.01" id="profit_margin" name="profit_margin"
                            value="{{ old('profit_margin', $product->profit_margin) }}"
                            class="form-control" required placeholder="Ex: 20">
                    </div>

                    <!-- Preço de Venda -->
                    <div class="col-md-4">
                        <label for="sale_price" class="form-label">Preço de Venda</label>
                        <input type="text" id="sale_price"
                            value="{{ number_format($product->sale_price ?? 0, 2, '.', '') }}"
                            class="form-control bg-light" disabled placeholder="Calculado automaticamente">
                    </div>

                    <!-- Estoque -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Estoque</label>
                        <input type="number" id="stock" name="stock"
                            value="{{ old('stock', $product->stock) }}"
                            class="form-control" required placeholder="Quantidade em estoque">
                    </div>
                </div>

                <!-- Botão -->
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script -->
<script>
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
