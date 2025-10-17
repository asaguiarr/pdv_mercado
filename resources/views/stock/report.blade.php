@extends('layouts.admin')

@section('title', 'Relatório de Estoque')

@section('content')
<div class="container py-4">

    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary mb-0">
            <i class="fas fa-warehouse me-2"></i>Relatório de Estoque
        </h1>
    </div>

    <!-- Card da Tabela -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Estoque Atual</th>
                            <th>Total Entradas</th>
                            <th>Total Saídas</th>
                            <th>Último Movimento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockReport as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->current_stock }}</td>
                            <td>{{ $item->total_in }}</td>
                            <td>{{ $item->total_out }}</td>
                            <td>
                                {{ $item->last_movement_date 
                                    ? \Carbon\Carbon::parse($item->last_movement_date)->format('d/m/Y H:i') 
                                    : 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                        @if(count($stockReport) === 0)
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhum registro encontrado.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
