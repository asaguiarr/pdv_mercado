@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-person-plus-fill text-success me-2"></i> Novo Cliente
        </h1>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left-circle me-1"></i> Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                {{-- Dados Pessoais --}}
                <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2">
                    <i class="bi bi-person-vcard me-2"></i> Informações Pessoais
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nome</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="contact" class="form-label fw-semibold">Contato</label>
                        <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') }}" required>
                        @error('contact')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="cpf" class="form-label fw-semibold">CPF</label>
                        <input type="text" id="cpf" name="cpf" class="form-control" value="{{ old('cpf') }}" maxlength="14" required>
                        @error('cpf')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="rg" class="form-label fw-semibold">RG</label>
                        <input type="text" id="rg" name="rg" class="form-control" value="{{ old('rg') }}" maxlength="12" required>
                        @error('rg')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="birthdate" class="form-label fw-semibold">Data de Nascimento</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                        @error('birthdate')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label fw-semibold">Endereço</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Foto --}}
                <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2">
                    <i class="bi bi-image me-2"></i> Foto do Cliente
                </h5>

                <div class="mb-4">
                    <label for="photo" class="form-label fw-semibold">Selecionar Foto</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <div class="mt-3 text-center">
                        <img id="photo-preview" src="#" alt="Preview da Foto" class="d-none rounded-circle shadow-sm border" width="140" height="140">
                    </div>
                    @error('photo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Botões --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success px-4 me-2">
                        <i class="bi bi-check-circle me-1"></i> Salvar
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if(file.size > 2 * 1024 * 1024){ // 2MB
        alert('A imagem deve ter no máximo 2MB.');
        event.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('photo-preview');
        output.src = reader.result;
        output.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

// Máscaras simples
document.addEventListener('DOMContentLoaded', function() {
    const cpfInput = document.getElementById('cpf');
    const rgInput = document.getElementById('rg');
    const contactInput = document.getElementById('contact');

    cpfInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2');
    });

    rgInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'');
    });

    contactInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'');
    });
});
</script>
@endpush
@endsection
