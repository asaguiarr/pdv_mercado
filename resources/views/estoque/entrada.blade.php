@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Entrada de Estoque</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('estoque.entrada.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="product_id">Produto</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Selecione um produto</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (Atual: {{ $product->quantity }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="quantidade">Quantidade a adicionar</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">Registrar Entrada</button>
        <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
