<?php

namespace App\Repository;
use App\Models\Permission;
class PermissionRepository{
    public function permissionCount(){
        return Permission::count();
    }

    public function getAllPermission(){
        return Permission::all();
    }

    public function getById($id){
        return Permission::find($id);
    }

    public function addOrUpdatePermission(array $data)
    {
        return Permission::addOrUpdate($data['permission'] ?? null,$data['id'] ?? null,$data['description'] ?? null);
    }

    public function deletePermission($id){
        return Permission::find($id)->delete();
    }

    public function permissionListQuery(){
        return Permission::leftJoin('users as addBy', 'permissions.add_by', 'addBy.id')->select('permissions.id', 'guard_name', 'permissions.name', 'addBy.name as addByName', 'permissions.created_at','permissions.description');

    }
}
