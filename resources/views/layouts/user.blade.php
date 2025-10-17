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
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar */
        .sidebar-icon {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .sidebar-icon:hover { transform: scale(1.1); }
        .bg-custom-sidebar { background-color: #2D3748; }

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

@php
    $sidebarMenus = [
        ['title'=>'Painel', 'route'=>'dashboard', 'icon'=>'fa-tachometer-alt', 'roles'=>['all']],
        ['title'=>'PDV', 'route'=>'pdv.sales', 'icon'=>'fa-cash-register', 'roles'=>['all']],
        ['title'=>'Produtos', 'route'=>'products.index', 'icon'=>'fa-box-open', 'roles'=>['admin','super_admin']],
        ['title'=>'Clientes', 'route'=>'customers.index', 'icon'=>'fa-users', 'roles'=>['admin','super_admin']],
        ['title'=>'Pedidos', 'route'=>'orders.index', 'icon'=>'fa-truck', 'roles'=>['admin','super_admin']],
        ['title'=>'Estoque', 'route'=>'estoque.index', 'icon'=>'fa-warehouse', 'roles'=>['estoquista','admin','super_admin']],
        ['title'=>'Administração', 'route'=>'admin.dashboard', 'icon'=>'fa-cog', 'roles'=>['super_admin']],
    ];
@endphp

<div id="app-container" class="d-flex">

    <!-- Sidebar -->
    <aside class="bg-custom-sidebar text-white p-3 d-flex flex-column justify-content-between" style="width: 80px;">
        <div>
            <div class="text-center fw-bold fs-4 text-warning mb-4">BP</div>
            <nav class="d-flex flex-column align-items-center gap-3">
                @foreach($sidebarMenus as $menu)
                    @if(in_array('all', $menu['roles']) || auth()->user()->hasAnyRole($menu['roles']))
                        <a href="{{ route($menu['route']) }}" class="sidebar-icon" title="{{ $menu['title'] }}">
                            <i class="fas {{ $menu['icon'] }} fs-5 text-light hover-text-warning"></i>
                        </a>
                    @endif
                @endforeach
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
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

@stack('scripts')

</body>
</html>
