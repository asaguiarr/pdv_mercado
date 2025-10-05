@extends('layouts.estoquista')

@section('content')
<div class="container mt-4">
    <h1>Entrada por Nota Fiscal</h1>

    <form action="{{ route('estoque.invoice_entrada.store') }}" method="POST" id="invoiceForm">
        @csrf

        <div class="row">
            <div class="col-md-4">
                <label for="invoice_number" class="form-label">Número da Nota Fiscal <span class="text-danger">*</span></label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number') }}" required>
                @error('invoice_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="supplier_id" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">Selecione</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="purchase_date" class="form-label">Data da Compra <span class="text-danger">*</span></label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                @error('purchase_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr>

        <h3>Produtos</h3>
        <div id="productsContainer">
            <div class="product-item row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Produto <span class="text-danger">*</span></label>
                    <select name="products[0][product_id]" class="form-control product-select" required>
                        <option value="">Selecione ou Novo</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                        <option value="new">Novo Produto</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantidade <span class="text-danger">*</span></label>
                    <input type="number" name="products[0][quantity]" class="form-control" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Preço Unitário</label>
                    <input type="number" name="products[0][unit_price]" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-md-3 new-product-fields" style="display: none;">
                    <label class="form-label">Nome do Novo Produto <span class="text-danger">*</span></label>
                    <input type="text" name="products[0][name]" class="form-control">
                    <input type="hidden" name="products[0][new_product]" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger remove-product" style="display: none;">Remover</button>
                </div>
            </div>
        </div>

        <button type="button" id="addProduct" class="btn btn-secondary mb-3">Adicionar Produto</button>

        <div>
            <button type="submit" class="btn btn-primary">Salvar Entrada</button>
            <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productIndex = 1;

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
        newItem.querySelector('.remove-product').style.display = 'block';
        container.appendChild(newItem);
        productIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.closest('.product-item').remove();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const item = e.target.closest('.product-item');
            const newFields = item.querySelector('.new-product-fields');
            const newProductInput = item.querySelector('input[name*="[new_product]"]');
            if (e.target.value === 'new') {
                newFields.style.display = 'block';
                newProductInput.value = '1';
                item.querySelector('input[name*="[name]"]').required = true;
            } else {
                newFields.style.display = 'none';
                newProductInput.value = '0';
                item.querySelector('input[name*="[name]"]').required = false;
            }
        }
    });
});
</script>
@endsection
