@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
<h1 class="h3 mb-4">➕ Criar Usuário</h1>

<form action="{{ route('users.store') }}" method="POST" class="card shadow-sm p-4">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirmar Senha</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Papéis</label>
        <select name="roles[]" class="form-select" multiple required>
            @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
            @endforeach
        </select>
        <small class="text-muted">Segure CTRL para selecionar múltiplos.</small>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
