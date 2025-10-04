<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="col-md-6 mb-3">
                            <label for="productPrice" class="form-label">Preço (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="productPrice" name="price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productStock" class="form-label">Estoque</label>
                            <input type="number" class="form-control" id="productStock" name="stock" required>
                        </div>
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