@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Registrar Entrada de Estoque</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('estoque.entrada.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="product_id" class="form-label">Produto</label>
            <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                <option value="">Selecione um produto</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Atual: {{ $product->quantity }})
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade a adicionar</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control @error('quantidade') is-invalid @enderror" value="{{ old('quantidade') }}" min="1" step="1" required>
            @error('quantidade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Registrar Entrada</button>
        <a href="{{ route('estoque.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
