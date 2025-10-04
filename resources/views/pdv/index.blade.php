@extends('layouts.app')

@section('title', 'PDV - Vendas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cash-register text-primary me-2"></i>
            Ponto de Venda
        </h1>
        <div>
            <a href="{{ route('pdv.sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nova Venda
            </a>
            <a href="{{ route('pdv.report') }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>Relatório
            </a>
            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('products.create') }}" class="btn btn-success">
                    <i class="fas fa-box me-2"></i>Produto
                </a>
            @endif
            @if(auth()->user()->hasRole('super_admin'))
                <a href="{{ route('admin.users.create') }}" class="btn btn-warning">
                    <i class="fas fa-user-plus me-2"></i>Usuário
                </a>
            @endif
            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                <a href="{{ route('customers.create') }}" class="btn btn-info">
                    <i class="fas fa-truck me-2"></i>Entrega
                </a>
            @endif
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar Venda</label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="ID ou nome do cliente..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary d-block">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Vendas -->
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Vendas Realizadas
            </h6>
        </div>
        <div class="card-body">
            @if($sales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Data/Hora</th>
                                <th>Cliente</th>
                                <th>Usuário</th>
                                <th>Forma de Pagamento</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#{{ $sale->id }}</span>
                                    </td>
                                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($sale->customer)
                                            {{ $sale->customer->name }}
                                        @else
                                            <span class="text-muted">Não informado</span>
                                        @endif
                                    </td>
                                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge
                                            @if($sale->payment_method == 'dinheiro') bg-success
                                            @elseif($sale->payment_method == 'cartao') bg-info
                                            @elseif($sale->payment_method == 'pix') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong>R$ {{ number_format($sale->total, 2, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pdv.sales.show', $sale->id) }}"
                                           class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma venda encontrada</h5>
                    <p class="text-muted">Comece criando sua primeira venda!</p>
                    <a href="{{ route('pdv.sales.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Criar Primeira Venda
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh a cada 30 segundos se não houver busca ativa
    @if(!request('search'))
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif
});
</script>
@endpush
@endsection
