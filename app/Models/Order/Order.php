<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\User;
use App\Models\CustomerOrder\CustomerOrder;

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
        'receiver_name',
        'receiver_phone',
        'receiver_email',
    ];

    protected $appends = ['owner_id'];

    protected $hidden = ['customer_orders', 'users'];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'shipping_date' => 'date',
            'estimated_delivery_date' => 'date',
            'delivery_date' => 'date',
        ];
    }

    /**
     * Convert model to array with id first
     */
    public function toArray(): array
    {
        $attributes = parent::toArray();

        // Remove customer_orders from response
        unset($attributes['customer_orders']);

        // Extract id if it exists
        if (isset($attributes['id'])) {
            $id = $attributes['id'];
            unset($attributes['id']);
            // Reorder with id first
            return array_merge(['id' => $id], $attributes);
        }

        return $attributes;
    }

    /**
     * Get owner_id from customer_order relationship
     */
    public function getOwnerIdAttribute()
    {
        return $this->customerOrders?->first()?->user_id;
    }

    /**
     * Get all customer orders for this order.
     */
    public function customerOrders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class, 'order_id');
    }

    /**
     * Get all users associated with this order through CustomerOrder.
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            CustomerOrder::class,
            'order_id',  // Foreign key on customer_orders table
            'id',        // Foreign key on users table
            'id',        // Local key on orders table
            'user_id'    // Local key on customer_orders table
        );
    }
}
