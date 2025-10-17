@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Registrar Saída de Estoque</h1>

    <form action="{{ route('estoque.saida.store_multiple') }}" method="POST" id="saidaForm">
        @csrf

        <div id="productsContainer">
            <div class="product-item row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Produto <span class="text-danger">*</span></label>
                    <select name="products[0][product_id]" class="form-control product-select" required>
                        <option value="">Selecione um produto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-quantity="{{ $product->quantity }}">
                                {{ $product->name }} (Estoque: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantidade <span class="text-danger">*</span></label>
                    <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-product d-none">Remover</button>
                </div>
            </div>
        </div>

        <button type="button" id="addProduct" class="btn btn-secondary mb-3">Adicionar Produto</button>

        <div>
            <button type="submit" class="btn btn-danger">Registrar Saída</button>
            <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productIndex = 1;

    // Adicionar novo produto
    document.getElementById('addProduct').addEventListener('click', function() {
        const container = document.getElementById('productsContainer');
        const newItem = container.querySelector('.product-item').cloneNode(true);
        const inputs = newItem.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + productIndex + ']');
                input.value = '';
            }
        });
        newItem.querySelector('.remove-product').classList.remove('d-none');
        container.appendChild(newItem);
        productIndex++;
    });

    // Remover produto
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.closest('.product-item').remove();
        }
    });

    // Atualizar max da quantidade de acordo com o estoque
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.selectedOptions[0];
            const maxQuantity = selectedOption.dataset.quantity || 1;
            const quantityInput = e.target.closest('.product-item').querySelector('.quantity-input');
            quantityInput.max = maxQuantity;
        }
    });
});
</script>
@endsection
