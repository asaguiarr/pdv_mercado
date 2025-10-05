@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<h1 class="h3 mb-4">✏️ Editar Usuário</h1>

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="card shadow-sm p-4">
    @csrf @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Senha (deixe em branco para não alterar)</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Confirmar Senha</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Papel</label>
        <select name="role" class="form-select" required>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Atualizar</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
