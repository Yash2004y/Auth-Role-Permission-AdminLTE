<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{

    public function find($id)
    {
        return User::find($id);
    }

    public function getUserRoleNames($user)
    {
        return $user->roles->pluck('name')->all();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->deleteImage();
        $user->delete();
    }

    public function userListQuery($role_id = null)
    {
        $data = User::with('roles')->leftJoin('users as addBy', 'users.add_by', 'addBy.id')
            ->select(['users.id', 'users.name', 'users.email', 'users.created_at', 'addBy.name as addByName', 'users.image']);
        $data->when($role_id, function ($q) use ($role_id) {
            $q->whereHas('roles', function ($q) use ($role_id) {
                $q->where('id', $role_id);
            });
        });
        return $data;
    }

    public function userCount(){
        return User::count();
    }

}
