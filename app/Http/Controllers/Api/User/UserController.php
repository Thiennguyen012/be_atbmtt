<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
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
        $limit = $request->query('limit', 10);
        $search = $request->query('search', '');

        $users = $this->userService->paginate($limit, $search);

        return response()->json([
            'status_code' => Response::HTTP_OK,
            'message' => 'Get users successfully',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:50|unique:users,phone',
                'password' => 'required|string|min:6',
                'birthday' => 'nullable|date',
                'address' => 'nullable|string|max:256',
                'avatar' => 'nullable|string',
                'status' => 'nullable|integer',
                'is_super_admin' => 'nullable|boolean',
            ], [
                'name.required' => 'Tên là bắt buộc',
                'name.string' => 'Tên phải là chuỗi ký tự',
                'name.max' => 'Tên không được vượt quá 255 ký tự',
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Email không hợp lệ',
                'email.unique' => 'Email này đã được sử dụng',
                'phone.required' => 'Số điện thoại là bắt buộc',
                'phone.unique' => 'Số điện thoại này đã được sử dụng',
                'phone.max' => 'Số điện thoại không được vượt quá 50 ký tự',
                'password.required' => 'Mật khẩu là bắt buộc',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
                'birthday.date' => 'Ngày sinh không hợp lệ',
                'address.max' => 'Địa chỉ không được vượt quá 256 ký tự',
            ]);

            $user = $this->userService->create($data);

            return response()->json([
                'status_code' => Response::HTTP_CREATED,
                'message' => 'Tạo người dùng thành công',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Lỗi khi tạo người dùng: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified user
     */
    public function show(string $id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            return response()->json([
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status_code' => Response::HTTP_OK,
            'message' => 'Get user successfully',
            'data' => $user
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, string $id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            return response()->json([
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|string|max:50|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:6',
            'birthday' => 'nullable|date',
            'address' => 'nullable|string|max:256',
            'avatar' => 'nullable|string',
            'status' => 'nullable|integer',
            'is_super_admin' => 'nullable|boolean',
        ]);

        $updatedUser = $this->userService->update($user, $data);

        return response()->json([
            'status_code' => Response::HTTP_OK,
            'message' => 'User updated successfully',
            'data' => $updatedUser
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(string $id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            return response()->json([
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->userService->delete($user);

        return response()->json([
            'status_code' => Response::HTTP_OK,
            'message' => 'User deleted successfully'
        ]);
    }
}
