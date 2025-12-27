<?php

namespace App\Interfaces;


interface PermissionServiceInterface
{
    public function updateOrCreatePermission(array $data);
    public function deletePermission(int $id);
}
