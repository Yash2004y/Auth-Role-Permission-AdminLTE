<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function createUser(array $data)
    {
        $input = $data;
        $image = $data['image'] ?? null;
        $input['password'] = Hash::make($input['password']);
        unset($input['image']);
        $user = $this->userRepository->create($input);
        if ($image) {
            $user->changeUserImage($image);
        }
        $user->syncRoles($data['roles']);
        return $user;
    }

    public function updateUser(int $id, array $data)
    {
        $input = $data;
        $image = $data['image'] ?? null;
        unset($input['image']);
        $user = $this->userRepository->update($id, $input);
        if ($image) {
            $user->changeUserImage($image);
        }
        $user->syncRoles($data['roles']);
        return $user;
    }

    public function deleteUser(int $id)
    {
        // Implementation for deleting a user
        $this->userRepository->delete($id);
    }
}
