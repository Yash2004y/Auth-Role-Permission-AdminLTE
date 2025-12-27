<?php

namespace App\Repository;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleRepository{

    public function getAllRoleNames(){
        return Role::pluck('name')->all();
    }

    public function roleCount(){
        return Role::count();
    }

    public function delete(int $id){
        return Role::find($id)->delete();
    }

    public function getRoleById(int $id): Role{
        return Role::find($id);
    }

    public function create(array $data): Role{
        return Role::create($data);
    }
    public function update(int $id, array $data): Role{
        $role = Role::find($id);
        $role->update($data);
        return $role;
    }

    public function getRolePermissionIds($id){
        return Role::find($id)?->permissions->pluck('id');
    }

    public function getRolePermissions($id){
    return Role::find($id)?->permissions;
    }

    public function roleListQuery($permissionIds){
        return Role::query()->leftJoin('users as addBy', 'roles.add_by', 'addBy.id')->select('roles.id', 'roles.created_at', 'roles.name', 'addBy.name as addByName', 'roles.description')
        ->when($permissionIds, function ($q) use ($permissionIds) {
            $q->whereIn('roles.id', DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->pluck('role_id'));
        });
    }
}
