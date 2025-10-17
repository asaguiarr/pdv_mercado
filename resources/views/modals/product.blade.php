<!-- Modal de Produto -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="productForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" id="productId" name="id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productCode" class="form-label">Código de Barras</label>
                        <input type="text" class="form-control" id="productCode" name="code">
                    </div>

                    <div class="mb-3">
                        <label for="productName" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="productCost" class="form-label">Preço de Custo (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="productCost" name="cost_price" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="productMargin" class="form-label">Margem (%)</label>
                            <input type="number" step="0.01" class="form-control" id="productMargin" name="profit_margin" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="productPrice" class="form-label">Preço de Venda (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="productPrice" name="sale_price" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="productStock" class="form-label">Estoque</label>
                        <input type="number" class="form-control" id="productStock" name="stock" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productModal = document.getElementById('productModal');
    const productForm = document.getElementById('productForm');
    const modalTitle = document.getElementById('productModalTitle');
    const productIdInput = document.getElementById('productId');
    const productCodeInput = document.getElementById('productCode');
    const productNameInput = document.getElementById('productName');
    const productCostInput = document.getElementById('productCost');
    const productMarginInput = document.getElementById('productMargin');
    const productPriceInput = document.getElementById('productPrice');
    const productStockInput = document.getElementById('productStock');

    // Função para calcular preço de venda automaticamente
    function calculateSalePrice() {
        const cost = parseFloat(productCostInput.value) || 0;
        const margin = parseFloat(productMarginInput.value) || 0;
        const salePrice = cost * (1 + margin / 100);
        productPriceInput.value = salePrice.toFixed(2);
    }

    // Atualiza preço de venda quando custo ou margem mudam
    productCostInput.addEventListener('input', calculateSalePrice);
    productMarginInput.addEventListener('input', calculateSalePrice);

    // Evento quando o modal abre
    productModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button?.getAttribute('data-id');

        if (id) {
            // Editar Produto
            modalTitle.textContent = 'Editar Produto';
            productIdInput.value = id;
            productCodeInput.value = button.getAttribute('data-code') || '';
            productNameInput.value = button.getAttribute('data-name');
            productCostInput.value = button.getAttribute('data-cost');
            productMarginInput.value = button.getAttribute('data-margin');
            productPriceInput.value = button.getAttribute('data-price');
            productStockInput.value = button.getAttribute('data-stock');
            productForm.action = `/products/${id}`;
            productForm.querySelector('input[name="_method"]').value = 'PUT';
        } else {
            // Novo Produto
            modalTitle.textContent = 'Novo Produto';
            productIdInput.value = '';
            productCodeInput.value = '';
            productNameInput.value = '';
            productCostInput.value = '';
            productMarginInput.value = '';
            productPriceInput.value = '';
            productStockInput.value = '';
            productForm.action = `/products`;
            productForm.querySelector('input[name="_method"]').value = 'POST';
        }
    });
});
</script>
