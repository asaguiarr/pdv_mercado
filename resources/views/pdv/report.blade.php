@extends('layouts.app')

@section('title', 'PDV - Relatório de Vendas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar text-primary me-2"></i>
            Relatório de Vendas
        </h1>
        <div>
            <button onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
            <button onclick="exportToExcel()" class="btn btn-info">
                <i class="fas fa-file-excel me-2"></i>Excel
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Data Inicial</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Data Final</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filtrar
                        </button>
                        <a href="{{ route('pdv.report') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser me-2"></i>Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total de Vendas</h6>
                            <h3 class="mb-0">{{ $sales->total() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Valor Total</h6>
                            <h3 class="mb-0">R$ {{ number_format($sales->sum('total'), 2, ',', '.') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Ticket Médio</h6>
                            <h3 class="mb-0">
                                @if($sales->count() > 0)
                                    R$ {{ number_format($sales->sum('total') / $sales->count(), 2, ',', '.') }}
                                @else
                                    R$ 0,00
                                @endif
                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-receipt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Descontos</h6>
                            <h3 class="mb-0">R$ {{ number_format($sales->sum('discount'), 2, ',', '.') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tags fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Vendas por Forma de Pagamento -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-pie me-2"></i>Vendas por Forma de Pagamento
            </h6>
        </div>
        <div class="card-body">
            <canvas id="paymentChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Lista de Vendas -->
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Detalhes das Vendas
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
                                <th class="text-end">Desconto</th>
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
                                        R$ {{ number_format($sale->discount, 2, ',', '.') }}
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
                        <tfoot class="table-light">
                            <tr class="table-active">
                                <th colspan="5" class="text-end">TOTAIS:</th>
                                <th class="text-end">R$ {{ number_format($sales->sum('discount'), 2, ',', '.') }}</th>
                                <th class="text-end">R$ {{ number_format($sales->sum('total'), 2, ',', '.') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma venda encontrada</h5>
                    <p class="text-muted">Não há vendas no período selecionado</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Gráfico de formas de pagamento
    const paymentData = @json($sales->groupBy('payment_method')->map(function($items) {
        return $items->count();
    }));

    const paymentLabels = Object.keys(paymentData);
    const paymentValues = Object.values(paymentData);

    if (paymentLabels.length > 0) {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: paymentValues,
                    backgroundColor: [
                        '#28a745', // dinheiro - verde
                        '#17a2b8', // cartao - azul
                        '#ffc107', // pix - amarelo
                        '#6c757d'  // outros - cinza
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Configurar datas padrão (hoje)
    if (!$('#start_date').val()) {
        const today = new Date().toISOString().split('T')[0];
        $('#start_date').val(today);
        $('#end_date').val(today);
    }
});

function exportToExcel() {
    // Implementação básica de exportação
    const table = document.querySelector('.table');
    const rows = table.querySelectorAll('tr');
    let csv = '';

    rows.forEach(row => {
        const cells = row.querySelectorAll('th, td');
        const rowData = Array.from(cells).map(cell => {
            // Remove HTML tags e espaços extras
            return cell.textContent.trim().replace(/,/g, ';');
        });
        csv += rowData.join(',') + '\n';
    });

    // Download do arquivo
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `relatorio-vendas-${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);

    showAlert('Relatório exportado com sucesso!');
}
</script>
@endpush
@endsection
