<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Services\Order\OrderService;
use App\Traits\ValidatesRequestData;
use App\CPU\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CustomerOrder\CustomerOrder;

class OrderController extends Controller
{
    use ValidatesRequestData;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', Helpers::LIMIT_PER_PAGE);
        $search = $request->query('search', '');

        $orders = $this->orderService->paginate($limit, $search);

        return $this->successResponse($orders, 'Lấy danh sách đơn hàng thành công');
    }

    /**
     * Store a newly created order (user_id is always the authenticated user)
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Get authenticated user's id
            $userId = auth('sanctum')->id();

            if (!$userId) {
                return $this->errorResponse('Bạn chưa xác thực', Response::HTTP_UNAUTHORIZED);
            }

            // Create order with customer order relationship
            $order = $this->orderService->createWithCustomer($validatedData, $userId);

            return $this->successResponse($order, 'Tạo đơn hàng thành công', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi tạo đơn hàng');
        }
    }

    /**
     * Get authenticated user's orders
     */
    public function myOrders(Request $request)
    {
        try {
            $userId = auth('sanctum')->id();

            if (!$userId) {
                return $this->errorResponse('Bạn chưa xác thực', Response::HTTP_UNAUTHORIZED);
            }

            $limit = $request->query('limit', Helpers::LIMIT_PER_PAGE);
            $search = $request->query('search', '');

            // Get order IDs where user is owner
            $orderIds = CustomerOrder::where('user_id', $userId)
                ->pluck('order_id')
                ->toArray();

            // Build query
            $query = \App\Models\Order\Order::whereIn('id', $orderIds)
                ->orderBy('created_at', 'desc');

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_name', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%');
                });
            }

            $orders = $query->paginate($limit);

            return $this->successResponse($orders, 'Lấy danh sách đơn hàng của bạn thành công');
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi lấy danh sách đơn hàng');
        }
    }

    /**
     * Display the specified order
     */
    public function show(string $id)
    {
        // Eager load users through CustomerOrder relationship
        $order = $this->orderService->find($id, ['users']);

        if (!$order) {
            return $this->errorResponse('Đơn hàng không tồn tại', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse($order, 'Lấy thông tin đơn hàng thành công');
    }

    /**
     * Update the specified order
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        try {
            $order = $this->orderService->find($id);

            if (!$order) {
                return $this->errorResponse('Đơn hàng không tồn tại', Response::HTTP_NOT_FOUND);
            }

            $updatedOrder = $this->orderService->update($order, $request->validated());

            return $this->successResponse($updatedOrder, 'Cập nhật đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi cập nhật đơn hàng');
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy(string $id)
    {
        try {
            $order = $this->orderService->find($id);

            if (!$order) {
                return $this->errorResponse('Đơn hàng không tồn tại', Response::HTTP_NOT_FOUND);
            }

            $this->orderService->delete($order);

            return $this->successResponse(null, 'Xóa đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi xóa đơn hàng');
        }
    }
}
