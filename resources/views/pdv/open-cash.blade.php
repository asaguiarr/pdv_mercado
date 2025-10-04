@extends('layouts.app')

@section('title', 'PDV - Abrir Caixa')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="h4 mb-0 text-primary">
                        <i class="fas fa-cash-register me-2"></i>
                        Abrir Caixa
                    </h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-box-open fa-4x text-success mb-3"></i>
                        <h5>Preparar para Iniciar as Vendas</h5>
                        <p class="text-muted">Informe o saldo inicial do caixa para começar</p>
                    </div>

                    <form method="POST" action="{{ route('pdv.open-cash.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="initial_balance" class="form-label">
                                <strong>Saldo Inicial do Caixa</strong>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">R$</span>
                                <input type="number"
                                       id="initial_balance"
                                       name="initial_balance"
                                       class="form-control @error('initial_balance') is-invalid @enderror"
                                       min="0"
                                       step="0.01"
                                       placeholder="0,00"
                                       required
                                       autofocus>
                                @error('initial_balance')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Digite o valor em dinheiro disponível no caixa para troco
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-play me-2"></i>
                                Abrir Caixa
                            </button>
                            <a href="{{ route('pdv.sales') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Informações Importantes
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Saldo Inicial:</strong> Valor em dinheiro disponível para troco
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Controle:</strong> Todas as vendas serão registradas neste caixa
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Relatório:</strong> Ao fechar o caixa, você receberá um relatório completo
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            <strong>Atenção:</strong> Só é possível ter um caixa aberto por vez
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Formatação do valor
    $('#initial_balance').on('input', function() {
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
    $('#initial_balance').on('blur', function() {
        const value = parseFloat($(this).val());
        if (value < 0) {
            showAlert('O saldo inicial não pode ser negativo', true);
            $(this).focus();
        }
    });
});
</script>
@endpush
@endsection
