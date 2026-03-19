<?php

namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\Base\BaseRepository;

class OrderRepository extends BaseRepository implements OrderInterface
{
    /**
     * Create a new class instance.
     */
    public function model()
    {
        return Order::class;
    }
}
