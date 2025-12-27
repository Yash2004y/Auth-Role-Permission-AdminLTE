<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use CommonModelTrait;

    protected static function booted(): void
    {
        static::created(function (Role $user) {
            $user->update(['add_by' => auth()?->user()?->id]);
        });
    }
    public function CreateBy()
    {
        return $this->belongsTo(User::class, 'add_by');
    }
}
