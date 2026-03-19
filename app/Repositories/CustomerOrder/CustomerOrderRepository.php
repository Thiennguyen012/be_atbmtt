<?php

namespace App\Repositories\CustomerOrder;

use App\Models\CustomerOrder\CustomerOrder;
use App\Repositories\Base\BaseRepository;

class CustomerOrderRepository extends BaseRepository implements CustomerOrderInterface
{
    /**
     * Create a new class instance.
     */
    public function model()
    {
        return CustomerOrder::class;
    }
}
