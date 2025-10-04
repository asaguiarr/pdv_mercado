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
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function histories()
    {
        return $this->hasMany(CustomerHistory::class);
    }

    public function recentHistories($limit = 10)
    {
        return $this->histories()->latest()->limit($limit)->get();
    }
}
