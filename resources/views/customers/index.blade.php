@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">📋 Lista de Clientes</h1>

    <div class="card shadow-sm p-4 glass-card">
        <div class="mb-3">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">➕ Novo Cliente</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Contato</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td><a href="{{ route('customers.show', $customer->id) }}">{{ $customer->name }}</a></td>
                            <td>{{ $customer->contact }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum cliente encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
}
</style>
@endsection
