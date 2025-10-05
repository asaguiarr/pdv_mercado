@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Controle de Estoque</h1>

    <a href="{{ route('estoque.entrada') }}" class="btn btn-success">Registrar Entrada Manual</a>
    <a href="{{ route('estoque.invoice_entrada') }}" class="btn btn-primary">Entrada por Nota Fiscal</a>
    <a href="{{ route('estoque.saida') }}" class="btn btn-danger">Registrar Saída</a>
    <a href="{{ route('estoque.relatorio') }}" class="btn btn-info">Relatório</a>

    <table class="table mt-3">
        <thead>
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
