@extends('layouts.app')

@section('title', 'PDV - Nova Venda')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-success me-2"></i>
            Nova Venda
        </h1>
        <a href="{{ route('pdv.sales') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="row">
        <!-- Busca de Produtos -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-search me-2"></i>Adicionar Produtos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <input type="text" id="product-search" class="form-control form-control-lg"
                                   placeholder="Buscar produto por nome ou código de barras...">
                        </div>
                        <div class="col-md-4">
                            <button id="search-btn" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Resultados da Busca -->
                    <div id="search-results" class="mb-4" style="display: none;">
                        <h6>Resultados da Busca:</h6>
                        <div id="products-list" class="row g-2"></div>
                    </div>
                </div>
            </div>

            <!-- Carrinho -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Carrinho
                    </h6>
                    <span class="badge bg-primary" id="cart-count">0 itens</span>
                </div>
                <div class="card-body">
                    <div id="cart-items"></div>

                    <div id="cart-empty" class="text-center py-4" style="display: none;">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Carrinho vazio</h5>
                        <p class="text-muted">Adicione produtos para começar</p>
                    </div>

                    <div id="cart-summary" class="mt-4" style="display: none;">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Subtotal:</strong>
                            <span id="cart-subtotal">R$ 0,00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Desconto:</strong>
                            <input type="number" id="cart-discount" class="form-control form-control-sm w-25 text-end"
                                   min="0" step="0.01" value="0" placeholder="0,00">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-success" id="cart-total">R$ 0,00</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finalizar Venda -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-check-circle me-2"></i>Finalizar Venda
                    </h6>
                </div>
                <div class="card-body">
                    <form id="checkout-form">
                        @csrf

                        <div class="mb-3">
                            <label for="customer-select" class="form-label">Cliente (Opcional)</label>
                            <select id="customer-select" name="customer_id" class="form-select">
                                <option value="">Selecionar cliente...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="payment-method" class="form-label">Forma de Pagamento</label>
                            <select id="payment-method" name="payment_method" class="form-select" required>
                                <option value="">Selecionar...</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="cartao">Cartão</option>
                                <option value="pix">PIX</option>
                                <option value="misto">Misto</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="delivery-type" class="form-label">Tipo de Entrega</label>
                            <select id="delivery-type" name="delivery_type" class="form-select" required>
                                <option value="balcao">Balcão</option>
                                <option value="entrega">Entrega</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="checkout-btn" class="btn btn-success btn-lg" disabled>
                                <i class="fas fa-cash-register me-2"></i>
                                Finalizar Venda
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Produto -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="product-details"></div>
                <div class="mt-3">
                    <label for="product-quantity" class="form-label">Quantidade</label>
                    <input type="number" id="product-quantity" class="form-control" min="1" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="add-to-cart">Adicionar ao Carrinho</button>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Modal de Senha Administrativa -->
@include('modals.admin-password')

@push('scripts')
<script>
let cart = [];
let selectedProduct = null;

$(document).ready(function() {
    // Busca de produtos
    $('#search-btn').click(function() {
        searchProducts();
    });

    $('#product-search').keypress(function(e) {
        if (e.which == 13) {
            searchProducts();
        }
    });

    // Finalizar venda
    $('#checkout-form').submit(function(e) {
        e.preventDefault();
        checkout();
    });

    // Atualizar desconto
    $('#cart-discount').on('input', function() {
        updateCartTotal();
    });

    // Atalhos de teclado
    $(document).keydown(function(e) {
        // F1 - Buscar produtos
        if (e.keyCode === 112) {
            e.preventDefault();
            $('#product-search').focus();
        }

        // F2 - Finalizar venda
        if (e.keyCode === 113) {
            e.preventDefault();
            if (cart.length > 0 && !$('#checkout-btn').prop('disabled')) {
                $('#checkout-btn').click();
            }
        }

        // F3 - Limpar carrinho
        if (e.keyCode === 114) {
            e.preventDefault();
            if (cart.length > 0) {
                showAdminPasswordModal(function() {
                    clearCart();
                    showAlert('Carrinho limpo com sucesso!', false);
                });
            }
        }

        // ESC - Fechar modais
        if (e.keyCode === 27) {
            $('.modal').modal('hide');
        }
    });
});

function searchProducts() {
    const search = $('#product-search').val();
    if (!search) {
        showAlert('Digite algo para buscar', true);
        return;
    }

    $.get(`{{ route('pdv.product.show') }}`, { search: search })
        .done(function(data) {
            displaySearchResults(data);
        })
        .fail(function() {
            showAlert('Erro ao buscar produtos', true);
        });
}

function displaySearchResults(products) {
    const container = $('#products-list');
    container.empty();

    if (products.length === 0) {
        container.append('<div class="col-12"><div class="alert alert-warning">Nenhum produto encontrado</div></div>');
    } else {
        products.forEach(product => {
            let barcodeHtml = '';
            if (product.code) {
                barcodeHtml = `<br><small class="text-muted">Cód: ${product.code}</small>`;
            }

            const productCard = `
                <div class="col-md-6">
                    <div class="card product-card" data-product='${JSON.stringify(product)}' style="cursor: pointer;">
                        <div class="card-body text-center">
                            <h6 class="card-title">${product.name}</h6>
                            <p class="text-success fw-bold">R$ ${parseFloat(product.sale_price).toFixed(2)}</p>
                            <small class="text-muted">Estoque: ${product.stock}</small>
                            ${barcodeHtml}
                        </div>
                    </div>
                </div>
            `;
            container.append(productCard);
        });

        // Click nos produtos
        $('.product-card').click(function() {
            selectedProduct = $(this).data('product');
            showProductModal(selectedProduct);
        });
    }

    $('#search-results').show();
}

function showProductModal(product) {
    let barcodeHtml = '';
    if (product.code) {
        barcodeHtml = `<p class="text-muted">Código de barras: ${product.code}</p>`;
    }

    $('#product-details').html(`
        <h5>${product.name}</h5>
        <p class="text-success fw-bold">R$ ${parseFloat(product.sale_price).toFixed(2)}</p>
        <p class="text-muted">Estoque disponível: ${product.stock}</p>
        ${barcodeHtml}
    `);
    $('#product-quantity').attr('max', product.stock);
    $('#productModal').modal('show');
}

$('#add-to-cart').click(function() {
    if (!selectedProduct) return;

    const quantity = parseInt($('#product-quantity').val());
    if (quantity <= 0 || quantity > selectedProduct.stock) {
        showAlert('Quantidade inválida', true);
        return;
    }

    addToCart(selectedProduct, quantity);
    $('#productModal').modal('hide');
    selectedProduct = null;
});

function addToCart(product, quantity) {
    const existingItem = cart.find(item => item.product_id === product.id);

    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            price: parseFloat(product.sale_price),
            quantity: quantity
        });
    }

    updateCartDisplay();
    updateCartTotal();
}

function updateCartDisplay() {
    const container = $('#cart-items');
    const empty = $('#cart-empty');
    const summary = $('#cart-summary');
    const count = $('#cart-count');

    if (cart.length === 0) {
        container.empty();
        empty.show();
        summary.hide();
        $('#checkout-btn').prop('disabled', true);
    } else {
        empty.hide();
        summary.show();
        $('#checkout-btn').prop('disabled', false);

        container.empty();
        cart.forEach((item, index) => {
            const itemHtml = `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                    <div class="flex-grow-1">
                        <strong>${item.name}</strong><br>
                        <div class="d-flex align-items-center">
                            <small class="text-muted me-2">R$ ${item.price.toFixed(2)} x </small>
                            <input type="number" min="1" value="${item.quantity}" class="form-control form-control-sm quantity-input" style="width: 60px;" onchange="updateQuantity(${index}, this.value)">
                        </div>
                    </div>
                    <div class="text-end">
                        <strong>R$ ${(item.price * item.quantity).toFixed(2)}</strong>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(${index})" title="Remover item (F3 para limpar tudo)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.append(itemHtml);
        });
    }

    count.text(`${cart.length} ${cart.length === 1 ? 'item' : 'itens'}`);
}

function removeFromCart(index) {
    showAdminPasswordModal(function() {
        cart.splice(index, 1);
        updateCartDisplay();
        updateCartTotal();
        showAlert('Produto removido com sucesso!', false);
    });
}

function updateQuantity(index, newQty) {
    const qty = parseInt(newQty);
    if (qty > 0) {
        cart[index].quantity = qty;
        updateCartDisplay();
        updateCartTotal();
    }
}

function clearCart() {
    cart = [];
    updateCartDisplay();
    updateCartTotal();
}

function updateCartTotal() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat($('#cart-discount').val()) || 0;
    const total = Math.max(subtotal - discount, 0);

    $('#cart-subtotal').text(`R$ ${subtotal.toFixed(2)}`);
    $('#cart-total').text(`R$ ${total.toFixed(2)}`);
}

function checkout() {
    if (cart.length === 0) {
        showAlert('Adicione produtos ao carrinho', true);
        return;
    }

    const paymentMethod = $('#payment-method').val();
    if (!paymentMethod) {
        showAlert('Selecione a forma de pagamento', true);
        return;
    }

    $('#checkout-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processando...');

    const data = {
        cart: cart,
        payment_method: paymentMethod,
        discount: parseFloat($('#cart-discount').val()) || 0,
        customer_id: $('#customer-select').val(),
        delivery_type: $('#delivery-type').val()
    };

    $.post('{{ route("pdv.sales.store") }}', data)
        .done(function(response) {
            showAlert('Venda realizada com sucesso!');
            setTimeout(() => {
                window.location.href = `/pdv/${response.sale.id}`;
            }, 1500);
        })
        .fail(function(xhr) {
            const error = xhr.responseJSON?.error || 'Erro ao processar venda';
            showAlert(error, true);
            $('#checkout-btn').prop('disabled', false).html('<i class="fas fa-cash-register me-2"></i>Finalizar Venda');
        });
}

function showAlert(message, isError = false) {
    const alertType = isError ? 'alert-danger' : 'alert-success';
    const alertHtml = `
        <div class="alert ${alertType} alert-dismissible fade show" role="alert">
            <i class="fas ${isError ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Remove alertas anteriores
    $('.alert').remove();

    // Adiciona novo alerta
    $('.container-fluid .row').first().before(alertHtml);

    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush
@endsection
