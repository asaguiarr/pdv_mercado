@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-6">📋 Lista de Clientes</h1>

    <div class="card p-6 shadow">

        {{-- Botão de Novo Cliente --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">➕ Novo Cliente</a>

            {{-- Pesquisa --}}
            <form action="{{ route('customers.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Pesquisar cliente..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary">🔍</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>E-mail</th>
                        <th>CPF</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr @if(!$customer->active) class="table-secondary" @endif>
                            <td>{{ $customer->id }}</td>
                            <td>
                                <a href="{{ route('customers.show', $customer->id) }}" class="text-decoration-none">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td>{{ $customer->contact }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->cpf }}</td>
                            <td>
                                @if($customer->active)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-info">👁️ Ver</a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este cliente?')">🗑️ Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Nenhum cliente encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $customers->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
