<?php

namespace App\Interfaces;


interface RoleServiceInterface
{
    public function createRole(array $data);
    public function updateRole(int $id, array $data);
    public function deleteRole(int $id);
}
