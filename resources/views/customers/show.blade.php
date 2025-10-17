@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Detalhes do Cliente</h1>

    {{-- Informações do Cliente --}}
    <div class="card mb-4 shadow-sm">
        <div class="row g-0">
            <div class="col-md-3 text-center p-3">
                @if($customer->photo)
                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto do Cliente" class="img-fluid rounded-circle shadow" style="max-width: 150px;">
                @else
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:150px; height:150px;">
                        Sem Foto
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h3 class="card-title">{{ $customer->name }}</h3>
                    <p class="mb-1"><strong>Email:</strong> {{ $customer->email }}</p>
                    <p class="mb-1"><strong>Contato:</strong> {{ $customer->contact }}</p>
                    <p class="mb-1"><strong>RG:</strong> {{ $customer->rg }}</p>
                    <p class="mb-1"><strong>CPF:</strong> {{ $customer->cpf }}</p>
                    <p class="mb-1"><strong>Data de Nascimento:</strong> {{ $customer->birthdate ? $customer->birthdate->format('d/m/Y') : 'Não informado' }}</p>
                    <p class="mb-0"><strong>Endereço:</strong> {{ $customer->address }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Pedidos Pendentes --}}
    <h4 class="mb-3">Pedidos Pendentes</h4>
    @if($pendingSales->count() > 0)
        <div class="row g-3 mb-4">
            @foreach($pendingSales as $sale)
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pedido #{{ $sale->id }}</h5>
                            <p class="card-text"><strong>Total:</strong> R$ {{ number_format($sale->total, 2, ',', '.') }}</p>
                            <p class="card-text"><small class="text-muted">{{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i') : 'Data não informada' }}</small></p>
                            <span class="badge bg-warning text-dark">Pendente</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Nenhum pedido pendente.</p>
    @endif

    {{-- Pagamentos Pendentes --}}
    <h4 class="mb-3">Pagamentos Pendentes</h4>
    @if($receivables->count() > 0)
        <div class="row g-3 mb-4">
            @foreach($receivables as $receivable)
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-start border-danger border-4">
                        <div class="card-body">
                            <h5 class="card-title">Conta a Receber #{{ $receivable->id }}</h5>
                            <p class="card-text"><strong>Valor:</strong> R$ {{ number_format($receivable->amount, 2, ',', '.') }}</p>
                            <p class="card-text"><small class="text-muted">{{ $receivable->due_date ? $receivable->due_date->format('d/m/Y') : 'Data não informada' }}</small></p>
                            <span class="badge bg-danger">Pendente</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Nenhum pagamento pendente.</p>
    @endif

    {{-- Histórico Recente --}}
    <h4 class="mb-3">Histórico Recente</h4>
    @if($histories->count() > 0)
        <div class="list-group mb-4">
            @foreach($histories as $history)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold">{{ ucfirst($history->action) }}</div>
                        <div class="small text-muted">{{ $history->description }}</div>
                    </div>
                    <small class="text-muted">{{ $history->created_at ? $history->created_at->format('d/m/Y H:i') : 'Data não informada' }}</small>
                </div>
            @endforeach
        </div>
    @else
        <p>Nenhum histórico disponível.</p>
    @endif

    <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
