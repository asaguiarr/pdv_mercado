@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">✏️ Editar Cliente</h1>

    <div class="card shadow-sm p-4 glass-card">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Contato</label>
                    <input type="text" name="contact" class="form-control" value="{{ old('contact', $customer->contact) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" value="{{ old('cpf', $customer->cpf) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">RG</label>
                    <input type="text" name="rg" class="form-control" value="{{ old('rg', $customer->rg) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $customer->birthdate ? $customer->birthdate->format('Y-m-d') : '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Endereço</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                <div class="mt-2">
                    <img id="photo-preview" src="{{ $customer->photo ? asset('storage/' . $customer->photo) : '#' }}"
                         alt="Preview da Foto" style="max-width:150px; border-radius:50%; {{ $customer->photo ? '' : 'display:none;' }}">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('photo-preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
}
</style>
@endsection
