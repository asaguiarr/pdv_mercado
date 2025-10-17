@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Controle de Estoque</h1>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('estoque.entrada') }}" class="btn btn-success">Registrar Entrada Manual</a>
            <a href="{{ route('estoque.invoice_entrada') }}" class="btn btn-primary">Entrada por Nota Fiscal</a>
            <a href="{{ route('estoque.saida') }}" class="btn btn-danger">Registrar Saída</a>
            <a href="{{ route('estoque.relatorio') }}" class="btn btn-info">Relatório</a>
        </div>
    </div>

    <table class="table table-striped table-hover datatable">
        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Quantidade em Estoque</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if ($(".datatable").length) {
            $(".datatable").DataTable({
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                }
            });
        }
    });
</script>
@endpush
