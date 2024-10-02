<?php


use Illuminate\Support\Facades\Route;
use LuizFabianoNogueira\AclPermissions\Http\Controllers\AclPermissionsController;

Route::controller(AclPermissionsController::class)
    ->name('acl-permissions.')
    ->prefix('acl-permissions')
    ->group(function () {

        Route::prefix('permissions')
            ->name('permissions.')
            ->group(function () {

                Route::get('/list', 'permissions')->name('list');
                Route::get('/create', 'permissionCreate')->name('create');
                Route::post('/store', 'permissionStore')->name('store');
                Route::post('/store-custom', 'permissionStoreCustom')->name('storeCustom');
                Route::delete('/destroy', 'permissionDestroy')->name('destroy');
        });

        Route::prefix('roles')
            ->name('roles.')
            ->group(function () {

                Route::get('/list', 'roles')->name('list');
                Route::get('/create', 'roleCreate')->name('create');
                Route::post('/store', 'roleStore')->name('store');
                Route::get('/active', 'roleActive')->name('active');
                Route::get('/role-x-permissions/{roleId}', 'rolePermissions')->name('rolePermissions');
                Route::post('/role-x-permissions-store', 'rolePermissionsStore')->name('rolePermissionsStore');
                Route::post('/edit', 'roleEdit')->name('edit');

        });

        Route::prefix('users')
            ->name('users.')
            ->group(function () {

                Route::get('/list', 'users')->name('list');
                Route::get('/user-x-roles/{id}', 'userRoles')->name('userRoles');
                Route::post('/user-x-roles', 'userRolesStore')->name('userRolesStore');
        });

});
