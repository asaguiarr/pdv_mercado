@extends('layouts.app')

@section('title', 'PDV - Fechar Caixa')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="h4 mb-0 text-primary">
                        <i class="fas fa-cash-register me-2"></i>
                        Fechar Caixa
                    </h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-box fa-4x text-warning mb-3"></i>
                        <h5>Fechamento do Caixa</h5>
                        <p class="text-muted">Confirme o saldo final para fechar o caixa</p>
                    </div>

                    <!-- Informações do Caixa -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Saldo Inicial</h6>
                                    <h4 class="text-success">R$ {{ number_format($cashStatus->initial_balance, 2, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Vendas do Dia</h6>
                                    <h4 class="text-primary">
                                        R$ {{ number_format(\App\Models\Sale::whereDate('created_at', today())->sum('total'), 2, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pdv.close-cash.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="closing_balance" class="form-label">
                                <strong>Saldo Final do Caixa</strong>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       id="closing_balance"
                                       name="closing_balance"
                                       class="form-control @error('closing_balance') is-invalid @enderror"
                                       min="0"
                                       step="0.01"
                                       placeholder="0,00"
                                       required
                                       autofocus>
                                @error('closing_balance')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Conte o dinheiro disponível no caixa e informe o valor total
                            </small>
                        </div>

                        <!-- Diferença -->
                        <div class="alert alert-info">
                            <strong>Diferença:</strong>
                            <span id="difference">R$ 0,00</span>
                            <small class="d-block mt-1">
                                Diferença = Saldo Final - (Saldo Inicial + Vendas)
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-stop me-2"></i>
                                Fechar Caixa
                            </button>
                            <a href="{{ route('pdv.sales') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumo das Vendas -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Resumo do Dia
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <h4 class="text-primary">{{ \App\Models\Sale::whereDate('created_at', today())->count() }}</h4>
                                <small class="text-muted">Total de Vendas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h4 class="text-success">
                                    R$ {{ number_format(\App\Models\Sale::whereDate('created_at', today())->sum('total'), 2, ',', '.') }}
                                </h4>
                                <small class="text-muted">Valor Total</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h4 class="text-info">
                                    R$ {{ number_format(\App\Models\Sale::whereDate('created_at', today())->avg('total'), 2, ',', '.') }}
                                </h4>
                                <small class="text-muted">Ticket Médio</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h4 class="text-warning">{{ \App\Models\Sale::whereDate('created_at', today())->sum('discount') }}</h4>
                                <small class="text-muted">Total em Descontos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const initialBalance = {{ $cashStatus->initial_balance }};
    const todaySales = {{ \App\Models\Sale::whereDate('created_at', today())->sum('total') }};

    // Calcular diferença automaticamente
    $('#closing_balance').on('input', function() {
        const closingBalance = parseFloat($(this).val()) || 0;
        const expected = initialBalance + todaySales;
        const difference = closingBalance - expected;

        const differenceElement = $('#difference');
        differenceElement.text(`R$ ${difference.toFixed(2)}`);

        if (difference > 0) {
            differenceElement.removeClass('text-danger').addClass('text-success');
        } else if (difference < 0) {
            differenceElement.removeClass('text-success').addClass('text-danger');
        } else {
            differenceElement.removeClass('text-success text-danger');
        }
    });

    // Formatação do valor
    $('#closing_balance').on('input', function() {
        let value = $(this).val();
        // Remove tudo que não é número ou ponto
        value = value.replace(/[^0-9.]/g, '');
        // Garante apenas um ponto
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        $(this).val(value);
    });

    // Validação em tempo real
    $('#closing_balance').on('blur', function() {
        const value = parseFloat($(this).val());
        if (value < 0) {
            showAlert('O saldo final não pode ser negativo', true);
            $(this).focus();
        }
    });
});
</script>
@endpush
@endsection
