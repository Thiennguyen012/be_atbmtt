<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\User\UserService;
use App\Traits\ValidatesRequestData;
use App\CPU\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use ValidatesRequestData;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', Helpers::LIMIT_PER_PAGE);
        $search = $request->query('search', '');

        $users = $this->userService->paginate($limit, $search);

        return $this->successResponse($users, 'Lấy danh sách người dùng thành công');
    }

    /**
     * Store a newly created user
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->create($request->validated());

            return $this->successResponse($user, 'Tạo người dùng thành công', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi tạo người dùng');
        }
    }

    /**
     * Display the specified user
     */
    public function show(string $id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            return $this->errorResponse('User không tồn tại', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse($user, 'Lấy thông tin người dùng thành công');
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = $this->userService->find($id);

            if (!$user) {
                return $this->errorResponse('User không tồn tại', Response::HTTP_NOT_FOUND);
            }

            $updatedUser = $this->userService->update($user, $request->validated());

            return $this->successResponse($updatedUser, 'Cập nhật người dùng thành công');
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi cập nhật người dùng');
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->userService->find($id);

            if (!$user) {
                return $this->errorResponse('User không tồn tại', Response::HTTP_NOT_FOUND);
            }

            $this->userService->delete($user);

            return $this->successResponse(null, 'Xóa người dùng thành công');
        } catch (\Exception $e) {
            return $this->handleException($e, 'Lỗi khi xóa người dùng');
        }
    }
}
