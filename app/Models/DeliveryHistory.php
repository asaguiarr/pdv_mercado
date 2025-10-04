<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryHistory extends Model
{
    use HasFactory;

    protected $table = 'delivery_history';

    protected $fillable = [
        'delivery_person_id',
        'order_id',
        'status',
        'notes',
    ];

    public function deliveryPerson()
    {
        return $this->belongsTo(DeliveryPerson::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
