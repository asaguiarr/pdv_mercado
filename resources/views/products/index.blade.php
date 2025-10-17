@extends('layouts.app')

@section('title', 'Gestão de Produtos')

@section('content')
<div class="container-fluid px-4 py-4 bg-light rounded shadow-sm">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h1 class="h3 fw-bold text-primary mb-0">Gestão de Produtos</h1>
        <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fas fa-plus me-2"></i>Novo Produto
        </button>
    </div>

    <!-- ALERTA DE CONFIRMAÇÃO -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    @endif

    <!-- Tabela de Produtos -->
    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Preço de Custo</th>
                            <th>Margem (%)</th>
                            <th>Valor de Venda</th>
                            <th>Estoque</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->code ?? 'N/A' }}</td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>R$ {{ number_format($product->cost_price, 2, ',', '.') }}</td>
                            <td>{{ $product->profit_margin }}%</td>
                            <td>
                                R$ {{ number_format($product->sale_price ?? ($product->cost_price * (1 + $product->profit_margin/100)), 2, ',', '.') }}
                            </td>
                            <td>{{ $product->stock }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
                                        data-id="{{ $product->id }}"
                                        data-code="{{ $product->code }}"
                                        data-name="{{ $product->name }}"
                                        data-cost="{{ $product->cost_price }}"
                                        data-margin="{{ $product->profit_margin }}"
                                        data-price="{{ $product->sale_price }}"
                                        data-stock="{{ $product->stock }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalTitle" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" id="productId" name="id">

        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold" id="productModalTitle">Novo Produto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="mb-3">
                    <label for="productCode" class="form-label">Código</label>
                    <input type="text" class="form-control" id="productCode" name="code">
                </div>
                <div class="mb-3">
                    <label for="productName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="productName" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="productCost" class="form-label">Preço de Custo</label>
                    <input type="number" step="0.01" class="form-control" id="productCost" name="cost_price" required>
                </div>
                <div class="mb-3">
                    <label for="productMargin" class="form-label">Margem de Lucro (%)</label>
                    <input type="number" step="0.01" class="form-control" id="productMargin" name="profit_margin" required>
                </div>
                <div class="mb-3">
                    <label for="productPrice" class="form-label">Valor de Venda</label>
                    <input type="number" step="0.01" class="form-control" id="productPrice" readonly>
                </div>
                <div class="mb-3">
                    <label for="productStock" class="form-label">Estoque</label>
                    <input type="number" class="form-control" id="productStock" name="stock" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productModal = document.getElementById('productModal');
    const costInput = document.getElementById('productCost');
    const marginInput = document.getElementById('productMargin');
    const priceInput = document.getElementById('productPrice');

    // cálculo automático do preço de venda
    function calcularPreco() {
        const custo = parseFloat(costInput.value) || 0;
        const margem = parseFloat(marginInput.value) || 0;
        const venda = custo * (1 + margem / 100);
        priceInput.value = venda.toFixed(2);
    }

    costInput.addEventListener('input', calcularPreco);
    marginInput.addEventListener('input', calcularPreco);

    productModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const modal = this;
        
        if (button.getAttribute('data-id')) {
            modal.querySelector('#productModalTitle').textContent = 'Editar Produto';
            modal.querySelector('#productId').value = button.getAttribute('data-id');
            modal.querySelector('#productCode').value = button.getAttribute('data-code') || '';
            modal.querySelector('#productName').value = button.getAttribute('data-name');
            modal.querySelector('#productCost').value = button.getAttribute('data-cost');
            modal.querySelector('#productMargin').value = button.getAttribute('data-margin');
            modal.querySelector('#productPrice').value = button.getAttribute('data-price');
            modal.querySelector('#productStock').value = button.getAttribute('data-stock');
            
            const form = modal.querySelector('form');
            form.action = "{{ url('products') }}/" + button.getAttribute('data-id');
            form.querySelector('input[name="_method"]').value = 'PUT';
        } else {
            modal.querySelector('#productModalTitle').textContent = 'Novo Produto';
            modal.querySelector('#productId').value = '';
            modal.querySelector('#productCode').value = '';
            modal.querySelector('#productName').value = '';
            modal.querySelector('#productCost').value = '';
            modal.querySelector('#productMargin').value = '';
            modal.querySelector('#productPrice').value = '';
            modal.querySelector('#productStock').value = '';
            
            const form = modal.querySelector('form');
            form.action = "{{ route('products.store') }}";
            form.querySelector('input[name="_method"]').value = 'POST';
        }
    });
});
</script>
@endpush
