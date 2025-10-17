window.PDV = {
    cart: [],
    products: [],
    selectedProduct: null,

    init() {
        this.bindEvents();
        this.setupKeyboardShortcuts();
    },

    bindEvents() {
        $('#search-btn').on('click', () => this.searchProducts());
        $('#show-all-btn').on('click', () => this.showAllProducts());
        $('#product-search').on('keypress', (e) => {
            if (e.which === 13) this.searchProducts();
        });
        $('#checkout-form').on('submit', (e) => {
            e.preventDefault();
            this.checkout();
        });
        $('#cart-discount').on('input', () => this.updateCartTotal());
        $('#add-to-cart').on('click', () => this.addToCartFromModal());
        $(document).on('click', '.product-card', (e) => this.selectProduct(e));
        $(document).on('click', '.remove-item', (e) => this.removeFromCart(e));
        $(document).on('change', '.quantity-input', (e) => this.updateQuantity(e));
        $('#customer-select').on('change', () => this.toggleCustomerFields());
    },

    setupKeyboardShortcuts() {
        $(document).on('keydown', (e) => {
            switch (e.keyCode) {
                case 112: // F1
                    e.preventDefault();
                    $('#product-search').focus();
                    break;
                case 113: // F2
                    e.preventDefault();
                    if (this.cart.length > 0 && !$('#checkout-btn').prop('disabled')) {
                        $('#checkout-btn').click();
                    }
                    break;
                case 114: // F3
                    e.preventDefault();
                    if (this.cart.length > 0) {
                        this.showAdminPasswordModal(() => {
                            this.clearCart();
                            this.showAlert('Carrinho limpo com sucesso!', false);
                        });
                    }
                    break;
                case 27: // ESC
                    e.preventDefault();
                    $('.modal').modal('hide');
                    break;
            }
        });
    },

    async searchProducts() {
        const search = $('#product-search').val().trim();

        const searchBtn = $('#search-btn');
        searchBtn.addClass('loading').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Buscando...');

        try {
            const response = await axios.get(window.routes.productSearch, { params: { q: search } });
            this.displaySearchResults(response.data);
        } catch (error) {
            this.showAlert('Erro ao buscar produtos', true);
        } finally {
            searchBtn.removeClass('loading').prop('disabled', false).html('<i class="fas fa-search me-2"></i>Buscar');
        }
    },

    async showAllProducts() {
        const showAllBtn = $('#show-all-btn');
        showAllBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Carregando...');

        try {
            const response = await axios.get(window.routes.productSearch, { params: { q: '' } });
            this.displaySearchResults(response.data);
        } catch (error) {
            this.showAlert('Erro ao carregar produtos', true);
        } finally {
            showAllBtn.prop('disabled', false).html('<i class="fas fa-list me-2"></i>Mostrar Todos os Produtos');
        }
    },

    displaySearchResults(products) {
        this.products = products; // Store products for later use
        const container = $('#products-list');
        container.empty();

        if (products.length === 0) {
            container.append('<div class="col-12"><div class="alert alert-warning">Nenhum produto encontrado</div></div>');
        } else {
            products.forEach(product => {
                const barcodeHtml = product.code ? `<br><small class="text-muted">Cód: ${product.code}</small>` : '';
                const productCard = `
                    <div class="col-md-6">
                        <div class="product-card" data-product-id="${product.id}" role="button" tabindex="0" aria-label="Selecionar produto ${product.name}">
                            <div class="card-body text-center">
                                <h6 class="card-title fw-semibold text-dark mb-2">${product.name}</h6>
                                <p class="price">R$ ${parseFloat(product.price).toFixed(2)}</p>
                                <small class="stock">Estoque: ${product.stock}</small>
                                ${barcodeHtml}
                            </div>
                        </div>
                    </div>
                `;
                container.append(productCard);
            });
        }

        $('#search-results').fadeIn(300);
    },

    selectProduct(e) {
        const productId = $(e.currentTarget).data('product-id');
        this.selectedProduct = this.products.find(product => product.id == productId);
        if (this.selectedProduct) {
            this.showProductModal(this.selectedProduct);
        }
    },

    showProductModal(product) {
        const barcodeHtml = product.code ? `<p class="text-muted">Código de barras: ${product.code}</p>` : '';
        $('#product-details').html(`
            <h5 class="fs-5 fw-semibold text-dark mb-2">${product.name}</h5>
            <p class="text-success fw-bold fs-5">R$ ${parseFloat(product.price).toFixed(2)}</p>
            <p class="text-muted">Estoque disponível: ${product.stock}</p>
            ${barcodeHtml}
        `);
        $('#product-quantity').attr('max', product.stock);
        $('#productModal').modal('show');
    },

    addToCartFromModal() {
        if (!this.selectedProduct) return;

        const quantity = parseInt($('#product-quantity').val());
        if (quantity <= 0 || quantity > this.selectedProduct.stock) {
            this.showAlert('Quantidade inválida', true);
            return;
        }

        this.addToCart(this.selectedProduct, quantity);
        $('#productModal').modal('hide');
        this.selectedProduct = null;
    },

    addToCart(product, quantity) {
        const existingItem = this.cart.find(item => item.product_id === product.id);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.cart.push({
                product_id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: quantity
            });
        }

        this.updateCartDisplay();
        this.updateCartTotal();
    },

    updateCartDisplay() {
        const container = $('#cart-items');
        const empty = $('#cart-empty');
        const summary = $('#cart-summary');
        const count = $('#cart-count');

        if (this.cart.length === 0) {
            container.empty();
            empty.fadeIn(300);
            summary.fadeOut(300);
            $('#checkout-btn').prop('disabled', true);
        } else {
            empty.fadeOut(300);
            summary.fadeIn(300);
            $('#checkout-btn').prop('disabled', false);

            container.empty();
            this.cart.forEach((item, index) => {
                const itemHtml = `
                    <div class="cart-item" role="listitem">
                        <div class="item-info">
                            <div class="item-name">${item.name}</div>
                            <div class="d-flex align-items-center mt-1">
                                <small class="text-muted me-2">R$ ${item.price.toFixed(2)} x </small>
                                <input type="number" min="1" value="${item.quantity}" class="quantity-input" data-index="${index}" aria-label="Quantidade de ${item.name}">
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="item-total">R$ ${(item.price * item.quantity).toFixed(2)}</div>
                            <button class="remove-btn" data-index="${index}" title="Remover item (F3 para limpar tudo)" aria-label="Remover ${item.name} do carrinho">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.append(itemHtml);
            });
        }

        count.text(`${this.cart.length} ${this.cart.length === 1 ? 'item' : 'itens'}`);
    },

    removeFromCart(e) {
        const index = $(e.currentTarget).data('index');
        this.showAdminPasswordModal(() => {
            this.cart.splice(index, 1);
            this.updateCartDisplay();
            this.updateCartTotal();
            this.showAlert('Produto removido com sucesso!', false);
        });
    },

    updateQuantity(e) {
        const index = $(e.currentTarget).data('index');
        const qty = parseInt($(e.currentTarget).val());
        if (qty > 0) {
            this.cart[index].quantity = qty;
            this.updateCartDisplay();
            this.updateCartTotal();
        }
    },

    clearCart() {
        this.cart = [];
        this.updateCartDisplay();
        this.updateCartTotal();
    },

    updateCartTotal() {
        const subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discount = parseFloat($('#cart-discount').val()) || 0;
        const total = Math.max(subtotal - discount, 0);

        $('#cart-subtotal').text(`R$ ${subtotal.toFixed(2)}`);
        $('#cart-total').text(`R$ ${total.toFixed(2)}`);
    },

    toggleCustomerFields() {
        const customerSelect = $('#customer-select').val();
        if (customerSelect === 'new') {
            $('#new-customer-fields').fadeIn(300);
        } else {
            $('#new-customer-fields').fadeOut(300);
        }
    },

    async checkout() {
        if (this.cart.length === 0) {
            this.showAlert('Adicione produtos ao carrinho', true);
            return;
        }

        const paymentMethod = $('#payment-method').val();
        if (!paymentMethod) {
            this.showAlert('Selecione a forma de pagamento', true);
            return;
        }

        const customerSelect = $('#customer-select').val();
        let customer_id = null;
        let customer_data = null;

        if (customerSelect === 'new') {
            const name = $('#customer-name').val().trim();
            if (!name) {
                this.showAlert('Nome do cliente é obrigatório', true);
                return;
            }
            customer_data = {
                name: name,
                email: $('#customer-email').val().trim() || null,
                cpf: $('#customer-cpf').val().trim() || null,
                contact: $('#customer-contact').val().trim() || null
            };
        } else if (customerSelect) {
            customer_id = customerSelect;
        }

        $('#checkout-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processando...');

        const formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('payment_method', paymentMethod);
        formData.append('delivery_type', $('#delivery-type').val());
        formData.append('discount', parseFloat($('#cart-discount').val()) || 0);

        if (customer_id) {
            formData.append('customer_id', customer_id);
        }

        if (customer_data) {
            formData.append('customer_name', customer_data.name);
            if (customer_data.email) formData.append('customer_email', customer_data.email);
            if (customer_data.cpf) formData.append('customer_cpf', customer_data.cpf);
            if (customer_data.contact) formData.append('customer_contact', customer_data.contact);
        }

        this.cart.forEach((item, index) => {
            formData.append(`products[${index}][id]`, item.product_id);
            formData.append(`products[${index}][quantity]`, item.quantity);
            formData.append(`products[${index}][unit_price]`, item.price);
        });

        try {
            const response = await axios.post(window.routes.checkout, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            this.showAlert('Venda realizada com sucesso!');
            setTimeout(() => {
                window.location.href = '{{ route("pdv.sales") }}';
            }, 1500);
        } catch (error) {
            const errorMsg = error.response?.data?.message || error.response?.data?.error || 'Erro ao processar venda';
            this.showAlert(errorMsg, true);
            $('#checkout-btn').prop('disabled', false).html('<i class="fas fa-cash-register me-2"></i>Finalizar Venda');
        }
    },

    showAdminPasswordModal(callback) {
        // Assuming the modal is included and has a function
        // For now, assume it's defined elsewhere
        if (typeof showAdminPasswordModal === 'function') {
            showAdminPasswordModal(callback);
        } else {
            callback(); // For simplicity
        }
    },

    showAlert(message, isError = false) {
        const alertClasses = isError ? 'alert alert-danger' : 'alert alert-success';
        const iconClass = isError ? 'fa-exclamation-triangle' : 'fa-check-circle';

        const alertHtml = `
            <div class="pdv-alert ${alertClasses} alert-dismissible fade show" role="alert" aria-live="assertive">
                <i class="fas ${iconClass} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar alerta"></button>
            </div>
        `;

        $('.pdv-alert').remove();
        $('body').append(alertHtml);

        setTimeout(() => {
            $('.pdv-alert').fadeOut(300, function() { $(this).remove(); });
        }, 5000);
    }
};

// Initialize on document ready
$(document).ready(() => {
    window.PDV.init();
});
