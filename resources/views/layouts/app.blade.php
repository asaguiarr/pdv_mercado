<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestão - Mercado Bom Preço')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar { width: 70px; min-height: 100vh; }
        .sidebar-icon { display: block; padding: 10px; text-align: center; transition: transform 0.2s; }
        .sidebar-icon:hover { transform: scale(1.2); color: #ffc107 !important; }
        .hover-text-warning:hover { color: #ffc107 !important; }
        .hover-text-danger:hover { color: #dc3545 !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-light text-dark">

@if(Request::is('login'))
    {{-- Layout limpo para login --}}
    @yield('content')
@else
    <div id="app-container" class="d-flex">

        <!-- Sidebar -->
        <aside class="bg-dark text-white p-3 d-flex flex-column justify-content-between sidebar">
            <div>
                <div class="text-center fw-bold fs-4 text-warning mb-4">BP</div>
                <nav class="d-flex flex-column align-items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="sidebar-icon" title="Painel"><i class="fas fa-tachometer-alt fs-5"></i></a>
                    <a href="{{ route('pdv.sales') }}" class="sidebar-icon" title="PDV"><i class="fas fa-cash-register fs-5"></i></a>
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                        <a href="{{ route('products.index') }}" class="sidebar-icon" title="Produtos"><i class="fas fa-box-open fs-5"></i></a>
                        <a href="{{ route('suppliers.index') }}" class="sidebar-icon" title="Fornecedores"><i class="fas fa-truck fs-5"></i></a>
                        <a href="{{ route('customers.index') }}" class="sidebar-icon" title="Clientes"><i class="fas fa-users fs-5"></i></a>
                        <a href="{{ route('orders.index') }}" class="sidebar-icon" title="Pedidos"><i class="fas fa-truck-loading fs-5"></i></a>
                    @endif
                    @if(auth()->user()->hasAnyRole(['estoquista','admin','super_admin']))
                        <a href="{{ route('estoque.entrada') }}" class="sidebar-icon" title="Entrada Estoque"><i class="fas fa-plus-circle fs-5"></i></a>
                        <a href="{{ route('estoque.saida') }}" class="sidebar-icon" title="Saída Estoque"><i class="fas fa-minus-circle fs-5"></i></a>
                    @endif
                </nav>
            </div>

            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-icon border-0 bg-transparent" title="Sair"><i class="fas fa-sign-out-alt fs-5"></i></button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow-1 p-3 p-md-4 overflow-auto">
            @yield('content')
        </main>
    </div>
@endif

<!-- MODALS -->

<!-- Modal Produto -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="productForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" id="productId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productCode" class="form-label">Código</label>
                        <input type="text" class="form-control" id="productCode" name="code">
                    </div>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nome</label>
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

<!-- Modal Cliente -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

<!-- Modal Pagamento (exemplo simples) -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalTitle">Registrar Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Valor</label>
                        <input type="number" class="form-control" id="paymentAmount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
                        <select class="form-select" id="paymentMethod" name="method">
                            <option value="dinheiro">Dinheiro</option>
                            <option value="cartao">Cartão</option>
                            <option value="pix">PIX</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Alert customizado -->
<div id="custom-alert" class="position-fixed top-0 end-0 m-3 alert alert-success alert-dismissible fade d-none" role="alert">
    <span id="custom-alert-message"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    // Alert customizado
    function showAlert(message, isError = false) {
        Swal.fire({ text: message, icon: isError ? 'error' : 'success', timer: 2500, showConfirmButton: false, position: 'top-end', toast: true });
    }

    // Inicializa DataTables
    document.addEventListener("DOMContentLoaded", function () {
        if ($(".datatable").length) {
            $(".datatable").DataTable({
                pageLength: 10,
                language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json" }
            });
        }
    });

    // Exemplo: Abrir modal Produto
    function showProductModal(callback) { window.productCallback = callback; var modal = new bootstrap.Modal(document.getElementById('productModal')); modal.show(); }

    // Exemplo: Abrir modal Cliente
    function showCustomerModal(callback) { window.customerCallback = callback; var modal = new bootstrap.Modal(document.getElementById('customerModal')); modal.show(); }

    // Exemplo: Abrir modal Pagamento
    function showPaymentModal(callback) { window.paymentCallback = callback; var modal = new bootstrap.Modal(document.getElementById('paymentModal')); modal.show(); }
</script>

@stack('scripts')
</body>
</html>
