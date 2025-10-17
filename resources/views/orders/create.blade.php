{{-- resources/views/orders/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Novo Pedido')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">📦 Novo Pedido</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Voltar
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Cliente</label>
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">Selecione um cliente</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Nome do Cliente</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="todo">A Fazer</option>
                            <option value="doing">Fazendo</option>
                            <option value="delivery">Entrega</option>
                            <option value="done">Concluído</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Itens do Pedido</label>
                        <div id="items-container">
                            <div class="item-row d-flex gap-2 mb-2">
                                <input type="text" name="items[0][product_name]" class="form-control" placeholder="Nome do Produto" required>
                                <input type="number" name="items[0][quantity]" class="form-control" placeholder="Quantidade" min="1" required>
                                <input type="number" name="items[0][price]" class="form-control" placeholder="Preço" step="0.01" required>
                                <button type="button" class="btn btn-danger remove-item">Remover</button>
                            </div>
                        </div>
                        <button type="button" id="add-item" class="btn btn-outline-primary">Adicionar Item</button>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Criar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'item-row d-flex gap-2 mb-2';
        newRow.innerHTML = `
            <input type="text" name="items[${itemIndex}][product_name]" class="form-control" placeholder="Nome do Produto" required>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Quantidade" min="1" required>
            <input type="number" name="items[${itemIndex}][price]" class="form-control" placeholder="Preço" step="0.01" required>
            <button type="button" class="btn btn-danger remove-item">Remover</button>
        `;
        container.appendChild(newRow);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endsection
