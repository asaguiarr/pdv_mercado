@extends('layouts.app')

@section('title', 'Histórico de Entregas')

@section('content')
<div class="container py-4">
    <h1>Histórico de Entregas</h1>

    @if($histories->count() > 0)
        <table class="table table-bordered datatable" id="historyTable">
            <thead>
                <tr>
                    <th>Entregador</th>
                    <th>Pedido</th>
                    <th>Status</th>
                    <th>Notas</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->deliveryPerson->name ?? 'N/A' }}</td>
                        <td>{{ $history->order->id ?? 'N/A' }}</td>
                        <td>
                            @if($history->status == 'pendente')
                                <span class="badge bg-warning text-dark">{{ ucfirst($history->status) }}</span>
                            @elseif($history->status == 'entregue')
                                <span class="badge bg-success">{{ ucfirst($history->status) }}</span>
                            @elseif($history->status == 'cancelado')
                                <span class="badge bg-danger">{{ ucfirst($history->status) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($history->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $history->notes }}</td>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted mt-4">
            <i class="fas fa-truck"></i> Nenhum histórico de entregas disponível.
        </p>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    if ($('#historyTable').length) {
        $('#historyTable').DataTable({
            pageLength: 10,
            order: [[4, 'desc']], // ordena pela data
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            columnDefs: [
                { orderable: false, targets: [2,3] } // status e notas não são ordenáveis
            ]
        });
    }
});
</script>
@endpush
