<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repository\PermissionRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
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
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;
    private PermissionRepository $permissionRepository;
    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository,PermissionRepository $permissionRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
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
            $listCount = this->userRepository->userCount();
            array_push($counts,[
                'permission'=>'user-list',
                'title'=>'Users',
                'icon'=>'fa-solid fa-users',
                'redirect'=>route('users.index'),
                'count'=>$listCount
            ]);
        }
         if($user->can('role-list')){
            $listCount = $this->roleRepository->roleCount();
            array_push($counts,[
                'permission'=>'role-list',
                'title'=>'Roles',
                'icon'=>'fa-solid fa-user-tag',
                'redirect'=>route('roles.index'),
                'count'=>$listCount
            ]);
        }
        if($user->can('permission-list')){
            $listCount = $this->permissionRepository->permissionCount();
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
