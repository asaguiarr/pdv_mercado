{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">Dashboard</h1>

    {{-- Cards principais --}}
    <div class="row">
        @foreach ($data['cards'] as $card)
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-{{ $card['color'] }} shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title">{{ $card['label'] }}</h6>
                            <h3 class="fw-bold">{{ $card['value'] }}</h3>
                        </div>
                        <i class="{{ $card['icon'] }} fa-2x"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Ações Rápidas --}}
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-bolt me-2"></i>Ações Rápidas
        </div>
        <div class="card-body">
            <div class="row g-3">
                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('products.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-box me-2"></i>
                            <br>Cadastrar Produto
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasRole('super_admin'))
                    <div class="col-md-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            <br>Cadastrar Usuário
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('customers.create') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-truck me-2"></i>
                            <br>Cadastrar Entrega
                        </a>
                    </div>
                @endif
                <div class="col-md-3">
                    <a href="{{ route('pdv.sales.create') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-cash-register me-2"></i>
                        <br>Nova Venda
                    </a>
                </div>
                @if(auth()->user()->hasAnyRole(['estoquista', 'admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('estoque.entrada') }}" class="btn btn-secondary btn-lg w-100">
                            <i class="fas fa-plus-circle me-2"></i>
                            <br>Entrada Estoque
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasAnyRole(['estoquista', 'admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('estoque.saida') }}" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-minus-circle me-2"></i>
                            <br>Saída Estoque
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('estoque.relatorio') }}" class="btn btn-dark btn-lg w-100">
                            <i class="fas fa-chart-line me-2"></i>
                            <br>Relatório Estoque
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-truck me-2"></i>
                            <br>Fornecedores
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('accounts_payable.index') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            <br>Contas a Pagar
                        </a>
                    </div>
                @endif
                @if(auth()->user()->hasAnyRole(['estoquista', 'admin', 'super_admin']))
                    <div class="col-md-3">
                        <a href="{{ route('estoque.invoice_entrada') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-file-invoice me-2"></i>
                            <br>Entrada NF
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Produtos com baixo estoque --}}
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-exclamation-triangle"></i> Produtos com baixo estoque
        </div>
        <div class="card-body">
            @if ($data['lowStockProducts']->count() > 0)
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Estoque</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['lowStockProducts'] as $produto)
                            <tr>
                                <td>{{ $produto->name }}</td>
                                <td>{{ $produto->stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Nenhum produto com estoque baixo 🎉</p>
            @endif
        </div>
    </div>

</div>
@endsection
