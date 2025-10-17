@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-truck me-2"></i>Editar Fornecedor</h4>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Voltar
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Endereço</label>
                    <textarea name="address" id="address" class="form-control">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cnpj" class="form-label">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" class="form-control" value="{{ old('cnpj', $supplier->cnpj) }}" placeholder="00.000.000/0000-00">
                    @error('cnpj')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="inscricao_estadual" class="form-label">Inscrição Estadual</label>
                    <input type="text" name="inscricao_estadual" id="inscricao_estadual" class="form-control" value="{{ old('inscricao_estadual', $supplier->inscricao_estadual) }}">
                    @error('inscricao_estadual')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="inscricao_municipal" class="form-label">Inscrição Municipal</label>
                    <input type="text" name="inscricao_municipal" id="inscricao_municipal" class="form-control" value="{{ old('inscricao_municipal', $supplier->inscricao_municipal) }}">
                    @error('inscricao_municipal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">Atualizar</button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
