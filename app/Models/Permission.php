<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use CommonModelTrait;

    public static function addOrUpdate($permissionName,$permissionId = 0,$description = null){
        $data = [
            'name'=>$permissionName,
            'description' => $description ?? null
        ];
        if($permissionId == 0){
            $data['add_by'] = auth()?->user()?->id ?? null;
        }
        return Permission::updateOrCreate(['id'=>$permissionId],$data);
    }
}
