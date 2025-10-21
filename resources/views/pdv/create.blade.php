@extends('layouts.app')

@section('title', 'PDV - Nova Venda')

@section('content')
<div class="pdv-container container-fluid py-4 px-lg-5">

    {{-- Cabeçalho --}}
    <div class="pdv-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 fw-bold text-primary mb-0">
            <i class="fas fa-cash-register me-2"></i>Ponto de Venda
        </h1>
        <div>
            <a href="{{ route('pdv.sales') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Voltar
            </a>
            <a href="{{ route('orders.create') }}" class="btn btn-info rounded-pill shadow-sm">
                <i class="fas fa-plus me-2"></i>Novo Pedido
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- 🔍 Busca e Carrinho --}}
        <div class="col-12 col-lg-8">

            {{-- Card: Buscar Produtos --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-gradient bg-primary text-white fw-semibold rounded-top-4">
                    <i class="fas fa-search me-2"></i>Buscar Produtos
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-12 col-md-8">
                            <label for="product-search" class="form-label fw-semibold">Produto</label>
                            <input type="text" id="product-search" class="form-control form-control-lg shadow-sm"
                                   placeholder="Nome, código ou código de barras..." autofocus>
                        </div>
                        <div class="col-12 col-md-4">
                            <button type="button" id="search-btn" class="btn btn-primary btn-lg w-100 shadow-sm">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-3">
                        <button id="show-all-btn" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Mostrar Todos os Produtos
                        </button>
                    </div>
                    </div>

                    <div id="search-results" class="d-none">
                        <h6 class="fw-semibold text-secondary border-bottom pb-2 mb-3">Resultados da busca</h6>
                        <div id="products-list" class="row g-3"></div>
                    </div>
                </div>
            </div>

            {{-- Card: Carrinho --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center rounded-top-4">
                    <span class="fw-semibold text-dark"><i class="fas fa-shopping-cart me-2 text-secondary"></i>Carrinho</span>
                    <span id="cart-count" class="badge bg-secondary bg-opacity-10 text-dark fs-6 fw-medium">0 itens</span>
                </div>

                <div class="card-body">
                    <div id="cart-items" class="list-group list-group-flush"></div>

                    {{-- Carrinho vazio --}}
                    <div id="cart-empty" class="text-center text-muted py-5">
                        <i class="fas fa-shopping-basket fa-3x mb-3 text-secondary"></i>
                        <h6 class="fw-semibold">Carrinho vazio</h6>
                        <p>Adicione produtos para começar</p>
                    </div>

                    {{-- Resumo --}}
                    <div id="cart-summary" class="d-none mt-4 border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal" class="fw-semibold">R$ 0,00</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label for="cart-discount" class="mb-0 fw-semibold">Desconto:</label>
                            <input type="number" id="cart-discount" class="form-control form-control-sm w-50 text-end"
                                   min="0" step="0.01" value="0" placeholder="0,00">
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3 fw-bold fs-5 text-primary">
                            <span>Total:</span>
                            <span id="cart-total">R$ 0,00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 💳 Finalização --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success bg-gradient text-white fw-semibold rounded-top-4">
                    <i class="fas fa-check-circle me-2"></i>Finalizar Venda
                </div>
                <div class="card-body">
                    <form id="checkout-form" class="vstack gap-3">
                        @csrf

                        <div>
                            <label for="customer-select" class="fw-semibold mb-1">Cliente</label>
                            <select id="customer-select" name="customer_id" class="form-select shadow-sm">
                                <option value="">Selecionar cliente...</option>
                                <option value="new">Adicionar novo cliente</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Campos para novo cliente --}}
                        <div id="new-customer-fields" class="d-none">
                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="text" id="customer-name" name="customer_name" class="form-control shadow-sm" placeholder="Nome do cliente" required>
                                </div>
                                <div class="col-6">
                                    <input type="email" id="customer-email" name="customer_email" class="form-control shadow-sm" placeholder="E-mail (opcional)">
                                </div>
                                <div class="col-6">
                                    <input type="text" id="customer-cpf" name="customer_cpf" class="form-control shadow-sm" placeholder="CPF (opcional)">
                                </div>
                                <div class="col-12">
                                    <input type="text" id="customer-contact" name="customer_contact" class="form-control shadow-sm" placeholder="Contato (opcional)">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="payment-method" class="fw-semibold mb-1">Forma de Pagamento</label>
                            <select id="payment-method" name="payment_method" class="form-select shadow-sm" required>
                                <option value="">Selecionar...</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix">PIX</option>
                                <option value="debito">Cartão Débito</option>
                                <option value="credito">Cartão Crédito</option>
                            </select>
                        </div>

                        <div>
                            <label for="delivery-type" class="fw-semibold mb-1">Tipo de Entrega</label>
                            <select id="delivery-type" name="delivery_type" class="form-select shadow-sm" required>
                                <option value="retirada">Balcão</option>
                                <option value="entrega">Entrega</option>
                            </select>
                        </div>

                        <button type="submit" id="checkout-btn"
                                class="btn btn-success btn-lg w-100 shadow-sm rounded-pill disabled"
                                disabled>
                            <i class="fas fa-cash-register me-2"></i>Finalizar Venda
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 🔧 Modal de Produto --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="productModalLabel"><i class="fas fa-box me-2"></i>Adicionar Produto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="product-details"></div>
                <div id="new-product-fields" class="d-none">
                    <h6>Adicionar Novo Produto</h6>
                    <div class="row g-2">
                        <div class="col-12">
                            <input type="text" id="new-product-name" class="form-control" placeholder="Nome do produto" required>
                        </div>
                        <div class="col-6">
                            <input type="number" id="new-product-price" class="form-control" placeholder="Preço unitário" step="0.01" required>
                        </div>
                        <div class="col-6">
                            <input type="number" id="new-product-stock" class="form-control" placeholder="Estoque inicial" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="product-quantity" class="form-label fw-semibold">Quantidade</label>
                    <input type="number" id="product-quantity" class="form-control shadow-sm" min="1" value="1">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button id="add-to-cart" type="button" class="btn btn-primary shadow-sm">
                    <i class="fas fa-cart-plus me-2"></i>Adicionar
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

@include('modals.admin-password')

@push('scripts')
<script>
    window.routes = {
        productSearch: '{{ route("pdv.products.index") }}',
        checkout: '{{ route("pdv.sales.store") }}'
    };
</script>
@vite(['resources/js/pdv.js'])
@endpush
@endsection
