<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsReceivable extends Model
{
    use HasFactory;

    protected $table = 'accounts_receivable';

    protected $fillable = [
        'customer_id',
        'order_id',
        'amount',
        'due_date',
        'paid_date',
        'status',
    ];

    /**
     * Customer relationship
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Order relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
