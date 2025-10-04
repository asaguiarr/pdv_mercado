@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Relatório de Estoque</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade Atual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr @if($p->quantity <= 5) class="table-danger" @endif>
                <td>{{ $p->name }}</td>
                <td>{{ $p->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
