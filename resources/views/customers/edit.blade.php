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
                    <div class="input-group">
                        <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact', $customer->contact) }}" required>
                        @if($customer->contact)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $customer->contact) }}" 
                           class="btn btn-success" target="_blank" data-bs-toggle="tooltip" title="Enviar WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" id="cpf" name="cpf" class="form-control" value="{{ old('cpf', $customer->cpf) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">RG</label>
                    <input type="text" id="rg" name="rg" class="form-control" value="{{ old('rg', $customer->rg) }}" required>
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
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script>
    // Inicializar tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    // Máscaras de input
    $(document).ready(function(){
        $('#cpf').inputmask('999.999.999-99');
        $('#rg').inputmask('99.999.999-9');
        $('#contact').inputmask('(99) 99999-9999');
    });

    // Pré-visualização da imagem e tamanho máximo
    function previewImage(event) {
        const file = event.target.files[0];
        if(file && file.size > 2 * 1024 * 1024){ // 2MB
            alert('A imagem deve ter no máximo 2MB.');
            event.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('photo-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
}
</style>
@endsection
