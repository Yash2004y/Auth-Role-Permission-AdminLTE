<?php

namespace App\Services;

use App\Interfaces\PermissionServiceInterface;
use App\Repository\PermissionRepository;

class PermissionService implements PermissionServiceInterface
{
    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function updateOrCreatePermission( array $data)
    {
        $this->permissionRepository->addOrUpdatePermission($data);
    }


    public function deletePermission(int $id)
    {
        return $this->permissionRepository->deletePermission($id);
    }

}
