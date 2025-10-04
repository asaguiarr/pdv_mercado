// ...

let cart = [];

$('#add-to-cart').on('click', function() {
    let productId = $('#product_id').val();
    let quantity = parseInt($('#quantity').val());

    $.ajax({
        type: 'GET',
        url: '/pdv/product/' + productId,
        success: function(product) {
            let existingItem = cart.find(item => item.product_id == productId);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    product_id: productId,
                    name: product.name,
                    price: product.sale_price,
                    quantity: quantity
                });
            }

            updateCart();
        }
    });
});

function updateCart() {
    $('#cart-table tbody').empty();

    let total = 0;

    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        $('#cart-table tbody').append(`
            <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>R$ ${item.price.toFixed(2).replace('.', ',')}</td>
                <td>R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">Remover</button>
                </td>
            </tr>
        `);
    });

    // Adicionei um elemento para exibir o total
    if ($('#total').length === 0) {
        $('#cart-table').after(`<p id="total">Total: R$ ${total.toFixed(2).replace('.', ',')}</p>`);
    } else {
        $('#total').text(`Total: R$ ${total.toFixed(2).replace('.', ',')}`);
    }
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

$('#finalize-sale').on('click', function() {
    if (cart.length === 0) {
        alert('Adicione pelo menos um produto ao carrinho.');
        return;
    }

    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = parseFloat($('#discount').val()) || 0;
    let finalTotal = Math.max(total - discount, 0);

    $('#modal-total').text(total.toFixed(2).replace('.', ','));
    $('#modal-discount').text(discount.toFixed(2).replace('.', ','));
    $('#modal-final-total').text(finalTotal.toFixed(2).replace('.', ','));
    $('#modal-payment-method').val($('#payment_method').val());

    $('#paymentModal').modal('show');
});

$('#modal-payment-method').on('change', function() {
    if ($(this).val() === 'dinheiro') {
        $('#change-section').show();
    } else {
        $('#change-section').hide();
    }
});

$('#received-amount').on('input', function() {
    let received = parseFloat($(this).val()) || 0;
    let finalTotal = parseFloat($('#modal-final-total').text().replace(',', '.')) || 0;
    let change = Math.max(received - finalTotal, 0);
    $('#change-amount').text(change.toFixed(2).replace('.', ','));
});

$('#confirm-payment').on('click', function() {
    let customerId = $('#customer_id').val();
    let paymentMethod = $('#modal-payment-method').val();
    let discount = parseFloat($('#discount').val()) || 0;

    $.ajax({
        type: 'POST',
        url: '/pdv/sale',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            cart: cart,
            customer_id: customerId,
            payment_method: paymentMethod,
            discount: discount
        },
        success: function(response) {
            alert(response.message);
            $('#paymentModal').modal('hide');
            cart = [];
            updateCart();
            $('#customer_id').val('');
            $('#discount').val('0');
            // Redirecione para a página de vendas
            window.location.href = '/pdv/sales';
        },
        error: function(xhr) {
            alert(xhr.responseJSON.error || 'Erro ao processar a venda.');
        }
    });
});
