<?php

namespace App\Models\CustomerOrder;

use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerOrder extends Model
{
    protected $table = 'customer_orders';

    protected $fillable = [
        'user_id',
        'order_id',
    ];

    /**
     * Get the user that owns the customer order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the customer order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
