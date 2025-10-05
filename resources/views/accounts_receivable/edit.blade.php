@extends('layouts.admin')

@section('title', 'Editar Conta a Receber')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Editar Conta a Receber</h1>
                <a href="{{ route('accounts_receivable.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Editar Conta a Receber</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('accounts_receivable.update', $receivable->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_id">Cliente</label>
                                    <select name="customer_id" id="customer_id" class="form-control" required>
                                        <option value="">Selecione um cliente</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $receivable->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="amount">Valor</label>
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $receivable->amount }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="due_date">Data de Vencimento</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $receivable->due_date->format('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending" {{ $receivable->status == 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="paid" {{ $receivable->status == 'paid' ? 'selected' : '' }}>Pago</option>
                                        <option value="overdue" {{ $receivable->status == 'overdue' ? 'selected' : '' }}>Vencido</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Descrição</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ $receivable->description }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
