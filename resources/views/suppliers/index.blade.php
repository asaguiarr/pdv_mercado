@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Fornecedores</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplierModal">
            <i class="fas fa-plus"></i> Cadastrar Fornecedor
        </button>
    </div>

    {{-- ALERTA DE CONFIRMAÇÃO --}}
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Endereço</th>
                            <th>CNPJ</th>
                            <th>Inscrição Estadual</th>
                            <th>Inscrição Municipal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->address }}</td>
                            <td>{{ $supplier->cnpj }}</td>
                            <td>{{ $supplier->inscricao_estadual }}</td>
                            <td>{{ $supplier->inscricao_municipal }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#supplierModal"
                                        data-id="{{ $supplier->id }}"
                                        data-name="{{ $supplier->name }}"
                                        data-email="{{ $supplier->email }}"
                                        data-phone="{{ $supplier->phone }}"
                                        data-address="{{ $supplier->address }}"
                                        data-cnpj="{{ $supplier->cnpj }}"
                                        data-inscricao_estadual="{{ $supplier->inscricao_estadual }}"
                                        data-inscricao_municipal="{{ $supplier->inscricao_municipal }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Nenhum fornecedor cadastrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE CADASTRO / EDIÇÃO --}}
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <input type="hidden" id="supplierId" name="id">
        <input type="hidden" name="_method" value="POST">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="supplierModalLabel">Novo Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="supplierName" class="form-label">Nome <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="supplierName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="supplierEmail" class="form-label">Email</label>
                    <input type="email" name="email" id="supplierEmail" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="supplierPhone" class="form-label">Telefone</label>
                    <input type="text" name="phone" id="supplierPhone" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="supplierAddress" class="form-label">Endereço</label>
                    <textarea name="address" id="supplierAddress" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="supplierCnpj" class="form-label">CNPJ</label>
                    <input type="text" name="cnpj" id="supplierCnpj" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="supplierIE" class="form-label">Inscrição Estadual</label>
                    <input type="text" name="inscricao_estadual" id="supplierIE" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="supplierIM" class="form-label">Inscrição Municipal</label>
                    <input type="text" name="inscricao_municipal" id="supplierIM" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const supplierModal = document.getElementById('supplierModal');

    supplierModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const modal = this;

        if (button.getAttribute('data-id')) {
            // Edição
            modal.querySelector('.modal-title').textContent = 'Editar Fornecedor';
            modal.querySelector('#supplierId').value = button.getAttribute('data-id');
            modal.querySelector('#supplierName').value = button.getAttribute('data-name');
            modal.querySelector('#supplierEmail').value = button.getAttribute('data-email');
            modal.querySelector('#supplierPhone').value = button.getAttribute('data-phone');
            modal.querySelector('#supplierAddress').value = button.getAttribute('data-address');
            modal.querySelector('#supplierCnpj').value = button.getAttribute('data-cnpj');
            modal.querySelector('#supplierIE').value = button.getAttribute('data-inscricao_estadual');
            modal.querySelector('#supplierIM').value = button.getAttribute('data-inscricao_municipal');

            const form = modal.querySelector('form');
            form.action = "{{ url('suppliers') }}/" + button.getAttribute('data-id');
            form.querySelector('input[name="_method"]').value = 'PUT';
        } else {
            // Novo fornecedor
            modal.querySelector('.modal-title').textContent = 'Novo Fornecedor';
            modal.querySelector('form').reset();
            modal.querySelector('#supplierId').value = '';
            const form = modal.querySelector('form');
            form.action = "{{ route('suppliers.store') }}";
            form.querySelector('input[name="_method"]').value = 'POST';
        }
    });
});
</script>
@endpush
