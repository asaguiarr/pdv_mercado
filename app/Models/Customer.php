<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact',
        'rg',
        'cpf',
        'birthdate',
        'address',
        'photo',
        'balance',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'balance' => 'decimal:2',
    ];

    public function histories()
    {
        return $this->hasMany(CustomerHistory::class);
    }

    public function recentHistories($limit = 10)
    {
        return $this->histories()->latest()->limit($limit)->get();
    }

    /**
     * Relacionamento com vendas
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Relacionamento com contas a receber
     */
    public function receivables()
    {
        return $this->hasMany(AccountsReceivable::class);
    }

    /**
     * Relacionamento com pedidos
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Calcular saldo devedor baseado em contas pendentes
     */
    public function calculateBalance()
    {
        $pendingReceivables = $this->receivables()->where('status', 'pending')->sum('amount');
        return $pendingReceivables;
    }

    /**
     * Atualizar saldo do cliente
     */
    public function updateBalance()
    {
        $this->balance = $this->calculateBalance();
        $this->save();
    }
}
