<?php

namespace App\Interfaces;

use App\Models\User;

interface UserServiceInterface
{
    public function createUser(array $data);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id);
}
