<?php

namespace LuizFabianoNogueira\AclPermissions\Services;

use LuizFabianoNogueira\AclPermissions\Models\Role;
use LuizFabianoNogueira\AclPermissions\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AclPermissionService
{
    /**
     * Load the permissions of the user
     *
     * @return void
     */
    public static function loadPermissions(): void
    {
        $permissions = [];
        if (Auth::check()) {
            foreach (Auth::user()->roles as $role) {
                foreach ($role->permissions as $permission) {
                    $permissions[$permission->module.".".$permission->controller.".".$permission->action] = "OK";
                }
            }
            Cache::put('acl-permissions-'.Auth::user()->id, $permissions);
        }
    }


    /**
     * Register the gates
     *
     * @return void
     */
    public static function registerGates(): void
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $permissionName = $permission->module.".".$permission->controller.".".$permission->action;
            Gate::define($permissionName, static function ($user) use ($permissionName) {
                if(Cache::has('acl-permissions-'.$user->id) === false) {
                    AclPermissionService::loadPermissions();
                }
                $permissionsCache = Cache::get('acl-permissions-'.$user->id);
                if (isset($permissionsCache[$permissionName])) {
                    return true;
                }
                return false;
            });
        }
    }
}
