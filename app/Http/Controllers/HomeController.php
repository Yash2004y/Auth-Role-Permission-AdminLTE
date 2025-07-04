<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Nette\Utils\ArrayList;
use Nette\Utils\Arrays;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $counts = $this->tableCounts();
        return view('dashboard',compact('counts'));
    }

    public function tableCounts(){

        $user = auth()->user();
        $counts=[];
        if($user->can('user-list')){
            $listCount = User::count();
            array_push($counts,[
                'permission'=>'user-list',
                'title'=>'Users',
                'icon'=>'fa-solid fa-users',
                'redirect'=>route('users.index'),
                'count'=>$listCount
            ]);
        }
         if($user->can('role-list')){
            $listCount = Role::count();
            array_push($counts,[
                'permission'=>'role-list',
                'title'=>'Roles',
                'icon'=>'fa-solid fa-user-tag',
                'redirect'=>route('roles.index'),
                'count'=>$listCount
            ]);
        }
        if($user->can('permission-list')){
            $listCount = Permission::count();
            array_push($counts,[
                'permission'=>'permission-list',
                'title'=>'Permissions',
                'icon'=>'fa-solid fa-key',
                'redirect'=>route('permissions.index'),
                'count'=>$listCount
            ]);
        }



        return $counts;
    }
}
