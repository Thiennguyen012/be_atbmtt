<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_name',
        'shipping_address',
        'status',
        'total_amount',
        'notes',
        'shipping_date',
        'estimated_delivery_date',
        'delivery_date',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'shipping_date' => 'date',
            'estimated_delivery_date' => 'date',
            'delivery_date' => 'date',
        ];
    }
}
