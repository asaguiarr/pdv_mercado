@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">➕ Criar Cliente</h1>

    <div class="card shadow-sm p-4 glass-card">
        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Contato</label>
                    <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" value="{{ old('cpf') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">RG</label>
                    <input type="text" name="rg" class="form-control" value="{{ old('rg') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Endereço</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                <div class="mt-2">
                    <img id="photo-preview" src="#" alt="Preview da Foto" style="display:none; max-width:150px; border-radius:50%;">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Salvar</button>
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
@endsection
