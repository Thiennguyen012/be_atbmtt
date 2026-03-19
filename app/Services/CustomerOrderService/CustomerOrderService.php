<?php

namespace App\Services\CustomerOrderService;

use App\Repositories\CustomerOrder\CustomerOrderInterface;

class CustomerOrderService
{
    protected $customerOrderRepository;

    public function __construct(CustomerOrderInterface $customerOrderRepository)
    {
        $this->customerOrderRepository = $customerOrderRepository;
    }

    /**
     * Get all customer orders
     */
    public function getAll($search = '')
    {
        $where = [];
        $orderBy = ['created_at' => 'desc'];

        if ($search) {
            $where['orWhere'] = [
                'user_id' => ['user_id', 'like', '%' . $search . '%'],
                'order_id' => ['order_id', 'like', '%' . $search . '%']
            ];
        }

        return $this->customerOrderRepository->get($where, $orderBy, ['*']);
    }

    /**
     * Get paginated customer orders
     */
    public function paginate($limit = 10, $search = '')
    {
        $where = [];
        $orderBy = ['created_at' => 'desc'];

        if ($search) {
            $where['orWhere'] = [
                'user_id' => ['user_id', 'like', '%' . $search . '%'],
                'order_id' => ['order_id', 'like', '%' . $search . '%']
            ];
        }

        return $this->customerOrderRepository->paginate($where, $orderBy, ['*'], [], $limit);
    }

    /**
     * Create a customer order
     */
    public function create($data)
    {
        return $this->customerOrderRepository->create($data);
    }

    /**
     * Find customer order by ID
     */
    public function find($id)
    {
        return $this->customerOrderRepository->find($id);
    }

    /**
     * Get all customer orders for a user
     */
    public function getByUserId($userId)
    {
        return $this->customerOrderRepository->first(['user_id' => $userId],[]);
    }

    /**
     * Get all customer orders for an order
     */
    public function getByOrderId($orderId)
    {
        return $this->customerOrderRepository->first(['order_id' => $orderId],[]);
    }

    /**
     * Update customer order
     */
    public function update($customerOrder, $data)
    {
        return $this->customerOrderRepository->edit($customerOrder, $data);
    }

    /**
     * Delete customer order
     */
    public function delete($customerOrder)
    {
        return $this->customerOrderRepository->delete($customerOrder);
    }

    /**
     * Restore deleted customer order
     */
    public function restore($customerOrder)
    {
        return $this->customerOrderRepository->restore($customerOrder);
    }

    /**
     * Force delete customer order
     */
    public function forceDelete($customerOrder)
    {
        return $this->customerOrderRepository->forceDelete($customerOrder);
    }
}
