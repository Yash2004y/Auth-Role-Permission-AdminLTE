<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use CommonModelTrait;

    protected static function booted(): void
    {
        static::created(function (Permission $user) {
            $user->update(['add_by' => auth()?->user()?->id]);
        });
    }
    public static function addOrUpdate($permissionName,$permissionId = 0,$description = null){
        $data = [
            'name'=>$permissionName,
            'description' => $description ?? null
        ];
        return Permission::updateOrCreate(['id'=>$permissionId],$data);
    }
}
