@extends('layouts.estoquista')

@section('content')
<div class="container mt-4">
    <h1>Registrar Saída de Estoque</h1>

    <form action="{{ route('estoque.saida.store') }}" method="POST" id="saidaForm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="customer_id" class="form-label">Cliente/Setor <span class="text-danger">*</span></label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Selecione</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="saida_date" class="form-label">Data da Saída <span class="text-danger">*</span></label>
                <input type="date" name="saida_date" id="saida_date" class="form-control" value="{{ old('saida_date', date('Y-m-d')) }}" required>
            </div>
        </div>

        <hr>

        <h3>Produtos</h3>
        <div id="productsContainer">
            <div class="product-item row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Produto <span class="text-danger">*</span></label>
                    <select name="products[0][product_id]" class="form-control product-select" required>
                        <option value="">Selecione</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-quantity="{{ $product->quantity }}">
                                {{ $product->name }} (Disponível: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantidade <span class="text-danger">*</span></label>
                    <input type="number" name="products[0][quantity]" class="form-control product-quantity" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-product d-none">Remover</button>
                </div>
            </div>
        </div>

        <button type="button" id="addProduct" class="btn btn-secondary mb-3">Adicionar Produto</button>

        <div>
            <button type="submit" class="btn btn-primary">Registrar Saída</button>
            <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productIndex = 1;

    // Adicionar produto
    document.getElementById('addProduct').addEventListener('click', function() {
        const container = document.getElementById('productsContainer');
        const newItem = container.querySelector('.product-item').cloneNode(true);
        const inputs = newItem.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, '[' + productIndex + ']');
                if(input.tagName === 'INPUT') input.value = '';
                if(input.tagName === 'SELECT') input.selectedIndex = 0;
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

    // Validar quantidade antes do submit
    document.getElementById('saidaForm').addEventListener('submit', function(e) {
        let valid = true;
        const items = document.querySelectorAll('.product-item');
        items.forEach(item => {
            const select = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.product-quantity');
            const available = parseInt(select.selectedOptions[0]?.dataset.quantity || 0);
            const requested = parseInt(quantityInput.value || 0);
            if(requested > available){
                valid = false;
                showAlert(`Quantidade solicitada de "${select.selectedOptions[0].text}" excede o estoque disponível (${available})!`, true);
            }
        });
        if(!valid) e.preventDefault();
    });
});
</script>
@endsection
