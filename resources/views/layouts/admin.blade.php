<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestão - Mercado Bom Preço')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar */
        .sidebar-icon {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .sidebar-icon:hover {
            transform: scale(1.1);
        }
        .bg-custom-sidebar {
            background-color: #2D3748;
        }

        /* Hover customizado */
        .hover-text-warning:hover { color: #ffc107 !important; }
        .hover-text-danger:hover { color: #dc3545 !important; }

        /* Scrollbar custom */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>

    @stack('styles')
</head>
<body class="bg-light">

<div id="app-container" class="d-flex">

    <!-- Sidebar -->
    <aside class="bg-custom-sidebar text-white p-3 d-flex flex-column justify-content-between" style="width: 80px;">
        <div>
            <div class="text-center fw-bold fs-4 text-warning mb-4">BP</div>
            <nav class="d-flex flex-column align-items-center gap-3">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="sidebar-icon" title="Painel">
                    <i class="fas fa-tachometer-alt fs-5 text-light hover-text-warning"></i>
                </a>

                <!-- PDV (Caixa) -->
                <a href="{{ route('pdv.sales') }}" class="sidebar-icon" title="PDV">
                    <i class="fas fa-cash-register fs-5 text-light hover-text-warning"></i>
                </a>

                <!-- Produtos (Admin/Super Admin) -->
                @if(Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('products.index') }}" class="sidebar-icon" title="Produtos">
                    <i class="fas fa-box-open fs-5 text-light hover-text-warning"></i>
                </a>
                @endif

                <!-- Fornecedores (Admin/Super Admin) -->
                @if(Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('suppliers.index') }}" class="sidebar-icon" title="Fornecedores">
                    <i class="fas fa-truck fs-5 text-light hover-text-warning"></i>
                </a>
                @endif

                <!-- Contas a Pagar (Admin/Super Admin) -->
                @if(Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('accounts_payable.index') }}" class="sidebar-icon" title="Contas a Pagar">
                    <i class="fas fa-money-bill-wave fs-5 text-light hover-text-warning"></i>
                </a>
                @endif

                <!-- Contas a Receber (Admin/Super Admin) -->
                @if(Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('accounts_receivable.index') }}" class="sidebar-icon" title="Contas a Receber">
                    <i class="fas fa-hand-holding-usd fs-5 text-light hover-text-warning"></i>
                </a>
                @endif

                <!-- Fluxo de Caixa (Admin/Super Admin) -->
                @if(Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('cash_flow.index') }}" class="sidebar-icon" title="Fluxo de Caixa">
                    <i class="fas fa-chart-line fs-5 text-light hover-text-warning"></i>
                </a>
                @endif

                <!-- Clientes (Admin/Super Admin) -->
                @if(Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('customers.index') }}" class="sidebar-icon" title="Clientes">
                    <i class="fas fa-users fs-5 text-light hover-text-warning"></i>
                </a>
                @endif

                <!-- Pedidos (Admin/Super Admin) -->
                <a href="{{ route('orders.index') }}" class="sidebar-icon" title="Pedidos">
                    <i class="fas fa-truck fs-5 text-light hover-text-warning"></i>
                </a>

                <!-- Estoque (Estoquista/Admin/Super Admin) -->
                <a href="{{ route('estoque.index') }}" class="sidebar-icon" title="Estoque">
                    <i class="fas fa-warehouse fs-5 text-light hover-text-warning"></i>
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-icon border-0 bg-transparent" title="Sair">
                    <i class="fas fa-sign-out-alt fs-5 text-light hover-text-danger"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 p-3 p-md-4 overflow-auto">
        @yield('content')
    </main>
</div>

<!-- Modals globais -->
@includeIf('modals.product')
@includeIf('modals.customer')
@includeIf('modals.payment')

<!-- Alert customizado -->
<div id="custom-alert" class="fixed-top top-0 end-0 m-3 alert alert-success alert-dismissible fade" role="alert">
    <span id="custom-alert-message"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- jQuery (necessário p/ DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    // Função de alerta customizado (fallback simples)
    function showAlert(message, isError = false) {
        Swal.fire({
            text: message,
            icon: isError ? 'error' : 'success',
            timer: 2500,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    }

    // Inicialização automática de DataTables em todas as tabelas com .datatable
    document.addEventListener("DOMContentLoaded", function () {
        if ($(".datatable").length) {
            $(".datatable").DataTable({
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                }
            });
        }
    });
</script>

{{-- Stack para cada view incluir JS próprio --}}
@stack('scripts')

</body>
</html>
