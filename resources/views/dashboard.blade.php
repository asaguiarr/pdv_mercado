{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-primary mb-1">
                <i class="fas fa-chart-line me-2"></i>Dashboard
            </h1>
            <p class="text-muted small mb-0">Bem-vindo, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <span class="badge bg-light text-secondary mt-3 mt-md-0 shadow-sm px-3 py-2">
            <i class="fas fa-calendar-alt me-2"></i>{{ now()->format('d/m/Y') }}
        </span>
    </div>

    {{-- Cards principais --}}
    <div class="row g-4 mb-5">
        @foreach ($data['cards'] as $card)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 hover-shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase fw-semibold text-muted small mb-2">{{ $card['label'] }}</p>
                            <h3 class="fw-bold mb-0 text-{{ $card['color'] }}">{{ $card['value'] }}</h3>
                        </div>
                        <div class="bg-{{ $card['color'] }} bg-opacity-10 rounded-circle p-3">
                            <i class="{{ $card['icon'] }} fs-3 text-{{ $card['color'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Ações Rápidas --}}
    <div class="card border-0 shadow-sm mb-5 rounded-3">
        <div class="card-header bg-primary text-white fw-semibold d-flex align-items-center">
            <i class="fas fa-bolt me-2"></i> Ações Rápidas
        </div>
        <div class="card-body py-4">
            <div class="row g-3">
                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-6 col-md-3">
                        <a href="{{ route('products.create') }}" class="btn btn-success w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-box fs-3 mb-2"></i>
                            Cadastrar Produto
                        </a>
                    </div>
                @endif

                @if(auth()->user()->hasRole('super_admin'))
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-warning w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-user-plus fs-3 mb-2"></i>
                            Cadastrar Usuário
                        </a>
                    </div>
                @endif

                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-6 col-md-3">
                        <a href="{{ route('customers.create') }}" class="btn btn-info w-100 d-flex flex-column align-items-center py-3 text-white">
                            <i class="fas fa-truck fs-3 mb-2"></i>
                            Cadastrar Entrega
                        </a>
                    </div>
                @endif

                <div class="col-6 col-md-3">
                    <a href="{{ route('pdv.sales.create') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center py-3">
                        <i class="fas fa-cash-register fs-3 mb-2"></i>
                        Nova Venda
                    </a>
                </div>

                @if(auth()->user()->hasAnyRole(['estoquista', 'admin', 'super_admin']))
                    <div class="col-6 col-md-3">
                        <a href="{{ route('estoque.entrada') }}" class="btn btn-secondary w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-plus-circle fs-3 mb-2"></i>
                            Entrada Estoque
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('estoque.saida') }}" class="btn btn-danger w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-minus-circle fs-3 mb-2"></i>
                            Saída Estoque
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('estoque.invoice_entrada') }}" class="btn btn-success w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-file-invoice fs-3 mb-2"></i>
                            Entrada NF
                        </a>
                    </div>
                @endif

                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-6 col-md-3">
                        <a href="{{ route('estoque.relatorio') }}" class="btn btn-secondary w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-chart-line fs-3 mb-2"></i>
                            Relatório Estoque
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-truck fs-3 mb-2"></i>
                            Fornecedores
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('accounts_payable.index') }}" class="btn btn-warning w-100 d-flex flex-column align-items-center py-3">
                            <i class="fas fa-money-bill-wave fs-3 mb-2"></i>
                            Contas a Pagar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Produtos com Baixo Estoque --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-warning text-dark fw-semibold d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i> Produtos com Baixo Estoque
        </div>
        <div class="card-body">
            @if ($data['lowStockProducts']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Estoque</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['lowStockProducts'] as $produto)
                                <tr>
                                    <td class="fw-semibold">{{ $produto->name }}</td>
                                    <td class="text-center text-danger fw-bold">{{ $produto->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-smile-beam fs-3 text-success mb-3 d-block"></i>
                    Nenhum produto com estoque baixo 🎉
                </div>
            @endif
        </div>
    </div>

</div>

{{-- CSS adicional para hover nos cards --}}
@push('styles')
<style>
.hover-shadow:hover {
    transform: translateY(-4px);
    transition: 0.3s;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}
</style>
@endpush

@endsection
