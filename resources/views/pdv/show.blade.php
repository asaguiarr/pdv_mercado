@extends('layouts.app')

@section('title', 'PDV - Detalhes da Venda #' . $sale->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-receipt text-primary me-2"></i>
            Venda #{{ $sale->id }}
        </h1>
        <div>
            <a href="{{ route('pdv.sales') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Voltar
            </a>
            <button onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Informações da Venda -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informações da Venda
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <strong>Data/Hora:</strong><br>
                            {{ $sale->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="col-12">
                            <strong>Usuário:</strong><br>
                            {{ $sale->user->name ?? 'N/A' }}
                        </div>
                        <div class="col-12">
                            <strong>Cliente:</strong><br>
                            @if($sale->customer)
                                {{ $sale->customer->name }}<br>
                                <small class="text-muted">{{ $sale->customer->email }}</small>
                            @else
                                <span class="text-muted">Não informado</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <strong>Forma de Pagamento:</strong><br>
                            <span class="badge
                                @if($sale->payment_method == 'dinheiro') bg-success
                                @elseif($sale->payment_method == 'cartao') bg-info
                                @elseif($sale->payment_method == 'pix') bg-warning
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($sale->payment_method) }}
                            </span>
                        </div>
                        <div class="col-12">
                            <strong>Desconto:</strong><br>
                            R$ {{ number_format($sale->discount, 2, ',', '.') }}
                        </div>
                        <div class="col-12">
                            <strong class="text-success fs-5">Total:</strong><br>
                            <strong class="text-success fs-4">R$ {{ number_format($sale->total, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Itens da Venda -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Itens da Venda
                    </h6>
                </div>
                <div class="card-body">
                    @if($sale->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-end">Preço Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product->name }}</strong>
                                                @if($item->product->code)
                                                    <br><small class="text-muted">Cód: {{ $item->product->code }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                            <td class="text-end">
                                                <strong>R$ {{ number_format($item->total_price, 2, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <th class="text-end">R$ {{ number_format($sale->items->sum('total_price'), 2, ',', '.') }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Desconto:</th>
                                        <th class="text-end">R$ {{ number_format($sale->discount, 2, ',', '.') }}</th>
                                    </tr>
                                    <tr class="table-success">
                                        <th colspan="3" class="text-end fs-5">TOTAL:</th>
                                        <th class="text-end fs-4">
                                            <strong>R$ {{ number_format($sale->total, 2, ',', '.') }}</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="text-muted">Nenhum item encontrado</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Movimentações -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Histórico de Movimentações
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Venda Realizada</h6>
                                <p class="timeline-text">
                                    Venda #{{ $sale->id }} processada com sucesso<br>
                                    <small class="text-muted">{{ $sale->created_at->format('d/m/Y H:i:s') }}</small>
                                </p>
                            </div>
                        </div>

                        @foreach($sale->items as $item)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Produto Vendido</h6>
                                    <p class="timeline-text">
                                        {{ $item->product->name }} - {{ $item->quantity }} unidade(s)<br>
                                        <small class="text-muted">Estoque reduzido</small>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-title {
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    margin-bottom: 0;
    color: #6c757d;
}

@media print {
    .btn, .card-header {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .table {
        font-size: 12px;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh para verificar status da venda
    setInterval(function() {
        // Pode ser implementado para verificar atualizações em tempo real
    }, 10000);
});
</script>
@endpush
@endsection
