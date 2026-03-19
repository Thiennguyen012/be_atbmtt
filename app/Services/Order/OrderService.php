<?php

namespace App\Services\Order;

use App\Repositories\Order\OrderInterface;
use App\Services\CustomerOrderService\CustomerOrderService;

class OrderService
{
    protected $orderRepository;
    protected $customerOrderService;

    public function __construct(OrderInterface $orderRepository, CustomerOrderService $customerOrderService)
    {
        $this->orderRepository = $orderRepository;
        $this->customerOrderService = $customerOrderService;
    }

    /**
     * Get all orders
     */
    public function getAll($search = '', $with = [])
    {
        $where = [];
        $orderBy = ['created_at' => 'desc'];

        if ($search) {
            $where['orWhere'] = [
                'id' => ['id', 'like', '%' . $search . '%'],
                'status' => ['status', 'like', '%' . $search . '%']
            ];
        }

        return $this->orderRepository->get($where, $orderBy, ['*'], $with);
    }

    /**
     * Get paginated orders
     */
    public function paginate($limit = 10, $search = '', $with = [])
    {
        $where = [];
        $orderBy = ['created_at' => 'desc'];

        if ($search) {
            $where['orWhere'] = [
                'id' => ['id', 'like', '%' . $search . '%'],
                'status' => ['status', 'like', '%' . $search . '%']
            ];
        }

        return $this->orderRepository->paginate($where, $orderBy, ['*'], $with, $limit);
    }

    /**
     * Find order by ID
     */
    public function find($id, $with = [])
    {
        if (!empty($with)) {
            return $this->orderRepository->first(['id' => $id], [], ['*'], $with);
        }
        return $this->orderRepository->find($id);
    }

    /**
     * Create a new order
     */
    public function create($data)
    {
        return $this->orderRepository->create($data);
    }

    /**
     * Create order with customer order relationship
     */
    public function createWithCustomer($orderData, $userId)
    {
        // Create order
        $order = $this->orderRepository->create($orderData);

        // Create customer order relationship
        if ($order) {
            $this->customerOrderService->create([
                'user_id' => $userId,
                'order_id' => $order->id
            ]);
        }

        return $order;
    }

    /**
     * Update order
     */
    public function update($order, $data)
    {
        return $this->orderRepository->edit($order, $data);
    }

    /**
     * Delete order
     */
    public function delete($order)
    {
        return $this->orderRepository->delete($order);
    }

    /**
     * Restore deleted order
     */
    public function restore($order)
    {
        return $this->orderRepository->restore($order);
    }

    /**
     * Force delete order
     */
    public function forceDelete($order)
    {
        return $this->orderRepository->forceDelete($order);
    }
}
