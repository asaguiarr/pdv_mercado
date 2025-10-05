@extends('layouts.admin')

@section('title', 'Detalhes da Conta a Receber')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Detalhes da Conta a Receber</h1>
                <a href="{{ route('accounts_receivable.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conta a Receber #{{ $receivable->id }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informações Gerais</h5>
                            <p><strong>Cliente:</strong> {{ $receivable->customer->name }}</p>
                            <p><strong>Valor:</strong> R$ {{ number_format($receivable->amount, 2, ',', '.') }}</p>
                            <p><strong>Data de Vencimento:</strong> {{ $receivable->due_date->format('d/m/Y') }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($receivable->status) }}</p>
                            @if($receivable->paid_date)
                                <p><strong>Data de Pagamento:</strong> {{ $receivable->paid_date->format('d/m/Y') }}</p>
                            @endif
                            @if($receivable->description)
                                <p><strong>Descrição:</strong> {{ $receivable->description }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($receivable->order)
                                <h5>Informações do Pedido</h5>
                                <p><strong>ID do Pedido:</strong> {{ $receivable->order->id }}</p>
                                <p><strong>Status do Pedido:</strong> {{ ucfirst($receivable->order->status) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('accounts_receivable.edit', $receivable->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @if($receivable->status == 'pending')
                            <form action="{{ route('accounts_receivable.markAsPaid', $receivable->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Marcar como Pago
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('accounts_receivable.destroy', $receivable->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta conta a receber?')">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
