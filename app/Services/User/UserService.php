<?php

namespace App\Services\User;

use App\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll($search = '')
    {
        $where = [];
        $orderBy = ['name' => 'asc'];

        if ($search) {
            $where['orWhere'] = [
                'name' => ['name', 'like', '%' . $search . '%'],
                'phone' => ['phone', 'like', '%' . $search . '%']
            ];
        }

        return $this->userRepository->get($where, $orderBy, ['*']);
    }

    public function paginate($limit = 10, $search = '')
    {
        $where = [];
        $orderBy = ['created_at' => 'desc'];

        if ($search) {
            $where['orWhere'] = [
                'name' => ['name', 'like', '%' . $search . '%'],
                'email' => ['email', 'like', '%' . $search . '%'],
                'phone' => ['phone', 'like', '%' . $search . '%']
            ];
        }

        return $this->userRepository->paginate($where, $orderBy, ['*'], [], $limit);
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function findByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findByPhone($phone)
    {
        return $this->userRepository->findByPhone($phone);
    }

    public function create($data)
    {
        // Xóa password_confirmation không cần thiết
        unset($data['password_confirmation']);

        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Tạo user
        return $this->userRepository->create($data);
    }

    public function update($user, $data)
    {
        // Xóa password_confirmation nếu có
        unset($data['password_confirmation']);

        // Hash password nếu có
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Cập nhật user
        return $this->userRepository->edit($user, $data);
    }

    public function delete($user)
    {
        return $this->userRepository->delete($user);
    }

    public function restore($user)
    {
        return $this->userRepository->restore($user);
    }

    public function forceDelete($user)
    {
        return $this->userRepository->forceDelete($user);
    }
}
