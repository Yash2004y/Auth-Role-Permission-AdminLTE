<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use CommonModelTrait;

    public function CreateBy(){
        return $this->belongsTo(User::class,'add_by');
    }
}
