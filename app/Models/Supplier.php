<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function accountsPayable()
    {
        return $this->hasMany(\App\Models\AccountsPayable::class);
    }
}
