@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4">✏️ Editar Cliente</h1>

    <div class="card shadow-sm p-4">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- Dados Pessoais --}}
            <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2">Informações Pessoais</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Contato</label>
                    <div class="input-group">
                        <input type="text" name="contact" class="form-control" value="{{ old('contact', $customer->contact) }}" required>
                        @if($customer->contact)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $customer->contact) }}" class="btn btn-success" target="_blank" title="Mensagem no WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        @endif
                    </div>
                    @error('contact')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" value="{{ old('cpf', $customer->cpf) }}" required>
                    @error('cpf')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">RG</label>
                    <input type="text" name="rg" class="form-control" value="{{ old('rg', $customer->rg) }}" required>
                    @error('rg')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $customer->birthdate) }}" required>
                    @error('birthdate')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Endereço</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}" required>
                    @error('address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Foto --}}
            <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2">Foto do Cliente</h5>
            <div class="mb-3">
                <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                <div class="mt-2">
                    <img id="photo-preview" 
                         src="{{ $customer->photo ? asset('storage/' . $customer->photo) : '#' }}" 
                         alt="Preview da Foto" 
                         style="width:150px; height:150px; border-radius:50%; object-fit:cover; {{ $customer->photo ? '' : 'display:none;' }}">
                </div>
            </div>

            {{-- Botões --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success me-2"><i class="bi bi-check-circle me-1"></i> Atualizar</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Preview da imagem
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('photo-preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Máscaras simples para CPF, RG e Contato
document.addEventListener('DOMContentLoaded', function() {
    const cpfInput = document.querySelector('input[name="cpf"]');
    const rgInput = document.querySelector('input[name="rg"]');
    const contactInput = document.querySelector('input[name="contact"]');

    cpfInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'')
                               .replace(/(\d{3})(\d)/,'$1.$2')
                               .replace(/(\d{3})(\d)/,'$1.$2')
                               .replace(/(\d{3})(\d{1,2})$/,'$1-$2');
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
