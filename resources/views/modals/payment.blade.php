<!-- Modal de Cliente -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="customerForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" id="customerId" name="id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="customerName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customerEmail" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="customerPhone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="customerPhone" name="phone">
                    </div>

                    <div class="mb-3">
                        <label for="customerAddress" class="form-label">Endereço</label>
                        <textarea class="form-control" id="customerAddress" name="address" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="customerCpfCnpj" class="form-label">CPF/CNPJ</label>
                        <input type="text" class="form-control" id="customerCpfCnpj" name="cpf_cnpj">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const customerModal = document.getElementById('customerModal');
    const customerForm = document.getElementById('customerForm');
    const modalTitle = document.getElementById('customerModalTitle');
    const customerIdInput = document.getElementById('customerId');
    const customerNameInput = document.getElementById('customerName');
    const customerEmailInput = document.getElementById('customerEmail');
    const customerPhoneInput = document.getElementById('customerPhone');
    const customerAddressInput = document.getElementById('customerAddress');
    const customerCpfCnpjInput = document.getElementById('customerCpfCnpj');

    customerModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button?.getAttribute('data-id');

        if (id) {
            // Editar Cliente
            modalTitle.textContent = 'Editar Cliente';
            customerIdInput.value = id;
            customerNameInput.value = button.getAttribute('data-name') || '';
            customerEmailInput.value = button.getAttribute('data-email') || '';
            customerPhoneInput.value = button.getAttribute('data-phone') || '';
            customerAddressInput.value = button.getAttribute('data-address') || '';
            customerCpfCnpjInput.value = button.getAttribute('data-cpf_cnpj') || '';
            customerForm.action = `/customers/${id}`;
            customerForm.querySelector('input[name="_method"]').value = 'PUT';
        } else {
            // Novo Cliente
            modalTitle.textContent = 'Novo Cliente';
            customerIdInput.value = '';
            customerNameInput.value = '';
            customerEmailInput.value = '';
            customerPhoneInput.value = '';
            customerAddressInput.value = '';
            customerCpfCnpjInput.value = '';
            customerForm.action = `/customers`;
            customerForm.querySelector('input[name="_method"]').value = 'POST';
        }
    });
});
</script>
