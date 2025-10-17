@extends('layouts.app')

@section('title', 'Adicionar Cliente')

@section('content')
<div class="container">
    <h1 class="mb-4">Adicionar Cliente</h1>
    
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf

        <!-- Nome -->
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control" 
                   value="{{ old('name') }}" 
                   placeholder="Digite o nome completo do cliente" 
                   required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control" 
                   value="{{ old('email') }}" 
                   placeholder="Digite o e-mail do cliente">
        </div>

        <!-- Telefone -->
        <div class="mb-3">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" 
                   name="phone" 
                   id="phone" 
                   class="form-control" 
                   value="{{ old('phone') }}" 
                   placeholder="(XX) XXXXX-XXXX">
        </div>

        <!-- CPF / CNPJ -->
        <div class="mb-3">
            <label for="cpf_cnpj" class="form-label">CPF / CNPJ</label>
            <input type="text" 
                   name="cpf_cnpj" 
                   id="cpf_cnpj" 
                   class="form-control" 
                   value="{{ old('cpf_cnpj') }}" 
                   placeholder="Digite CPF ou CNPJ">
        </div>

        <!-- Inscrição Estadual -->
        <div class="mb-3">
            <label for="inscricao_estadual" class="form-label">Inscrição Estadual</label>
            <input type="text" 
                   name="inscricao_estadual" 
                   id="inscricao_estadual" 
                   class="form-control" 
                   value="{{ old('inscricao_estadual') }}" 
                   placeholder="Digite a inscrição estadual">
        </div>

        <!-- Endereço -->
        <div class="mb-3">
            <label for="address" class="form-label">Endereço</label>
            <input type="text" 
                   name="address" 
                   id="address" 
                   class="form-control" 
                   value="{{ old('address') }}" 
                   placeholder="Rua, número, bairro">
        </div>

        <!-- Cidade -->
        <div class="mb-3">
            <label for="city" class="form-label">Cidade</label>
            <input type="text" 
                   name="city" 
                   id="city" 
                   class="form-control" 
                   value="{{ old('city') }}" 
                   placeholder="Cidade">
        </div>

        <!-- Estado -->
        <div class="mb-3">
            <label for="state" class="form-label">Estado</label>
            <select name="state" id="state" class="form-select">
                <option value="">Selecione</option>
                @foreach($states as $uf)
                    <option value="{{ $uf }}" {{ old('state') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                @endforeach
            </select>
        </div>

        <!-- CEP -->
        <div class="mb-3">
            <label for="zip_code" class="form-label">CEP</label>
            <input type="text" 
                   name="zip_code" 
                   id="zip_code" 
                   class="form-control" 
                   value="{{ old('zip_code') }}" 
                   placeholder="00000-000">
        </div>

        <!-- Botão -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Salvar
            </button>
        </div>
    </form>
</div>
@endsection
