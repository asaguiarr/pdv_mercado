@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<h1 class="text-2xl font-bold mb-6">✏️ Editar Usuário</h1>

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="card p-6 shadow">
    @csrf @method('PUT')

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Nome</label>
        <input type="text" name="name" class="input input-bordered w-full" value="{{ $user->name }}" required>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-semibold">E-mail</label>
        <input type="email" name="email" class="input input-bordered w-full" value="{{ $user->email }}" required>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Senha (deixe em branco para não alterar)</label>
        <input type="password" name="password" class="input input-bordered w-full">
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-semibold">Confirmar Senha</label>
        <input type="password" name="password_confirmation" class="input input-bordered w-full">
    </div>

    <div class="mb-6">
        <label class="block mb-1 font-semibold">Papel</label>
        <select name="role" class="select select-bordered w-full" required>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-6">
        <label class="flex items-center">
            <input type="checkbox" name="active" value="1" {{ $user->active ? 'checked' : '' }} class="checkbox">
            <span class="ml-2">Usuário Ativo</span>
        </label>
    </div>

    <button type="submit" class="btn btn-success mr-2">Atualizar</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
