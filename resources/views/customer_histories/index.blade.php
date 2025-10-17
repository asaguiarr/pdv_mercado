@extends('layouts.app')

@section('title', 'Histórico do Cliente')

@section('content')
<div class="container py-4">
    <h1>Histórico do Cliente: {{ $customer->name }}</h1>

    <a href="{{ route('customers.index') }}" class="btn btn-secondary mb-3">← Voltar para Clientes</a>

    @if($histories->count() > 0)
        <!-- Botões de alternância -->
        <div class="mb-3">
            <button id="tableViewBtn" class="btn btn-outline-primary btn-sm me-2">Tabela</button>
            <button id="cardViewBtn" class="btn btn-outline-secondary btn-sm">Cards</button>
        </div>

        <!-- Tabela -->
        <div id="tableView">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Ação</th>
                        <th>Descrição</th>
                        <th>Usuário Responsável</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ ucfirst($history->action) }}</td>
                            <td>{{ $history->description }}</td>
                            <td>{{ $history->user->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('customer_histories.show', [$customer->id, $history->id]) }}" class="btn btn-primary btn-sm">Detalhes</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $histories->links() }}
            </div>
        </div>

        <!-- Cards -->
        <div id="cardView" class="row g-3" style="display:none;">
            @foreach($histories as $history)
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <span class="badge bg-info text-dark">{{ ucfirst($history->action) }}</span>
                            </h5>
                            <p class="card-text">{{ $history->description }}</p>
                            <p class="text-muted mb-1">
                                <strong>Data:</strong> {{ $history->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-muted mb-2">
                                <strong>Usuário:</strong> {{ $history->user->name ?? 'N/A' }}
                            </p>
                            <a href="{{ route('customer_histories.show', [$customer->id, $history->id]) }}" class="btn btn-primary btn-sm">
                                Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">
            Nenhum histórico encontrado para este cliente.
        </div>
    @endif
</div>

@push('scripts')
<script>
    const tableBtn = document.getElementById('tableViewBtn');
    const cardBtn = document.getElementById('cardViewBtn');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');

    tableBtn.addEventListener('click', () => {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
        tableBtn.classList.add('btn-outline-primary');
        tableBtn.classList.remove('btn-outline-secondary');
        cardBtn.classList.add('btn-outline-secondary');
        cardBtn.classList.remove('btn-outline-primary');
    });

    cardBtn.addEventListener('click', () => {
        tableView.style.display = 'none';
        cardView.style.display = 'flex';
        cardBtn.classList.add('btn-outline-primary');
        cardBtn.classList.remove('btn-outline-secondary');
        tableBtn.classList.add('btn-outline-secondary');
        tableBtn.classList.remove('btn-outline-primary');
    });
</script>
@endpush
@endsection
