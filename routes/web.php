<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes([
    'register' => false,      // Disable Registration
    'reset' => false,         // Disable Password Reset
    'verify' => false,        // Disable Email Verification
    'confirm' => false,       // Disable Password Confirmation
]);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::prefix('roles')->middleware("can:role-list")->name('roles.')->group(function () {
        Route::post('list', [RoleController::class, 'list'])->name('list');
        Route::resource('/', RoleController::class)->parameters(['' => 'role']);
    });

    Route::prefix('users')->middleware("can:user-list")->name('users.')->group(function () {
        Route::post('list', [UserController::class, 'list'])->name('list');
        Route::resource('/', UserController::class)->parameters(['' => 'user']);
    });

    Route::prefix('permissions')->middleware("can:role-list")->name('permissions.')->group(function () {
        Route::post('list', [PermissionController::class, 'list'])->name('list');
        Route::post('create', [PermissionController::class, 'create'])->name('create');
        Route::post('edit/{id}', [PermissionController::class, 'edit'])->name('edit');
        Route::resource('/', PermissionController::class)->except('create','edit')->parameters(['' => 'permission']);
    });
});
