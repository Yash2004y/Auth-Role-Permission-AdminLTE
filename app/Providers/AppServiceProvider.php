<?php

namespace App\Providers;

use App\Interfaces\PermissionServiceInterface;
use App\Interfaces\RoleServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(UserServiceInterface::class,UserService::class);
        app()->bind(RoleServiceInterface::class,RoleService::class);
        app()->bind(PermissionServiceInterface::class,PermissionService::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('hasAnyPermission', function (...$permissions) {
            return Auth::check() && Auth::user()->hasAnyPermission($permissions);
        });
        //
    }
}
