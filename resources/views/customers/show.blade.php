@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
<div class="container py-4">
    <h1>Detalhes do Cliente</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h3>{{ $customer->name }}</h3>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Contato:</strong> {{ $customer->contact }}</p>
            <p><strong>RG:</strong> {{ $customer->rg }}</p>
            <p><strong>CPF:</strong> {{ $customer->cpf }}</p>
            <p><strong>Data de Nascimento:</strong> {{ $customer->birthdate->format('d/m/Y') }}</p>
            <p><strong>Endereço:</strong> {{ $customer->address }}</p>
            @if($customer->photo)
                <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto do Cliente" class="img-thumbnail" style="max-width: 200px;">
            @endif
        </div>
    </div>

    <h2>Pedidos Pendentes</h2>
    @if($pendingSales->count() > 0)
        <ul class="list-group">
            @foreach($pendingSales as $sale)
                <li class="list-group-item">
                    <strong>Pedido #{{ $sale->id }}</strong> - Total: R$ {{ number_format($sale->total, 2, ',', '.') }} <br>
                    <small class="text-muted">{{ $sale->created_at->format('d/m/Y H:i') }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhum pedido pendente.</p>
    @endif

    <h2>Pagamentos Pendentes</h2>
    @if($receivables->count() > 0)
        <ul class="list-group">
            @foreach($receivables as $receivable)
                <li class="list-group-item">
                    <strong>Conta a Receber #{{ $receivable->id }}</strong> - Valor: R$ {{ number_format($receivable->amount, 2, ',', '.') }} <br>
                    <small class="text-muted">{{ $receivable->due_date->format('d/m/Y') }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhum pagamento pendente.</p>
    @endif

    <h2>Histórico Recente</h2>
    @if($histories->count() > 0)
        <ul class="list-group">
            @foreach($histories as $history)
                <li class="list-group-item">
                    <strong>{{ ucfirst($history->action) }}</strong> - {{ $history->description }} <br>
                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhum histórico disponível.</p>
    @endif

    <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
