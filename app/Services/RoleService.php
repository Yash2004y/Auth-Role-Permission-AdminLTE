<?php

namespace App\Services;

use App\Interfaces\RoleServiceInterface;
use App\Repository\RoleRepository;

class RoleService implements RoleServiceInterface
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function createRole(array $data)
    {
        // Implementation for creating a role
         $permissionsID = array_map(
                function ($value) {
                    return (int)$value;
                },
                $data['permission'] ?? []
            );

            $role = $this->roleRepository->create($data);
            $role->syncPermissions($permissionsID);
    }

    public function updateRole(int $id, array $data)
    {
        // Implementation for updating a role
        $role = $this->roleRepository->update($id, $data);
        $permissionsID = array_map(
            function ($value) {
                return (int)$value;
            },
            ($data['permission'] ?? [])
        );

        $role->syncPermissions($permissionsID);
        return $role;
    }

    public function deleteRole(int $id)
    {
        $this->roleRepository->delete($id);
        // Implementation for deleting a role
    }
}
