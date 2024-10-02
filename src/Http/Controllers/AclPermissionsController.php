<?php

namespace LuizFabianoNogueira\AclPermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LuizFabianoNogueira\AclPermissions\Models\Permission;
use LuizFabianoNogueira\AclPermissions\Models\Role;

class AclPermissionsController extends Controller
{
    private $modelUser;
    private $modelRole;

    public function __construct()
    {
        $mu = config('acl-permissions.user');
        if (!$mu) {
            $this->modelUser = new User();
        } else {
            $this->modelUser = new $mu();
        }

        $mr = config('acl-permissions.role');
        if (!$mr) {
            $this->modelRole = new Role();
        } else {
            $this->modelRole = new $mr();
        }
    }

    /**
     * Show the permissions
     *
     * @param Request $request
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function permissions(Request $request)
    {
        $routeList = $this->getRouteList();
        $permissionsGetList = Permission::get();
        $permissions = [];
        foreach ($permissionsGetList as $permissionsItem) {
            $permissions[$permissionsItem->module][$permissionsItem->controller][$permissionsItem->action] = $permissionsItem->id;
        }
        $newPermissions = [];
        foreach ($routeList as $module => $moduleList) {
            foreach ($moduleList as $controller => $controllerList) {
                foreach ($controllerList as $action => $actionContent) {
                    if (!isset($permissions[$module][$controller][$action])) {
                        $newPermissions[$module][$controller][$action] = 1;
                    }
                }
            }
        }

        return view('acl-permissions.permissions', compact('permissions', 'routeList', 'newPermissions'));
    }

    public function permissionCreate()
    {

    }

    /**
     *
     * Save the permissions
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function permissionStore(Request $request): RedirectResponse
    {
        $permissions = $request->permission;
        if (is_array($permissions)) {
            foreach ($permissions as $module => $moduleList) {
                foreach ($moduleList as $controller => $controllerList) {
                    foreach ($controllerList as $action => $actionContent) {
                        $permission = Permission::where('module', $module)
                            ->where('controller', $controller)
                            ->where('action', $action)
                            ->first();
                        if (!$permission) {
                            Permission::create([
                                'name' => ucfirst($module) . ' ' . ucfirst($controller) . ' ' . ucfirst($action),
                                'description' => 'Auto generated',
                                'module' => strtolower($module),
                                'controller' => strtolower($controller),
                                'action' => strtolower($action),
                            ]);
                        }
                    }
                }
            }
        }

        return response()->redirectToRoute('acl-permissions.permissions.show');
    }

    /**
     *
     * Delete the permission
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function permissionDestroy(Request $request)
    {
        $permission = Permission::find($request->id);
        if ($permission) {
            $permission->delete();
        }
        return response()->redirectToRoute('acl-permissions.permissions.show');
    }

    /**
     * @param Request $request
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function users(Request $request)
    {
        $users = $this->modelUser->orderBy('name')->get();
        return view('acl-permissions.users', [
            'users' => $users
        ]);
    }

    public function userRoles(Request $request, $id)
    {
        $user = $this->modelUser->find($id);
        $roles = $this->modelRole->all();
        return view('acl-permissions.user-x-roles', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     *
     * Show the roles
     *
     * @param Request $request
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function roles(Request $request)
    {
        $roles = $this->modelRole->orderBy('name')->get();
        return view('acl-permissions.roles', [
            'roles' => $roles
        ]);
    }

    public function roleCreate()
    {
        return view('acl-permissions.role-create');
    }

    public function roleStore(Request $request): RedirectResponse
    {
        if($request->has('id') && !empty($request->id)) {
            $role = $this->modelRole->where('id', $request->id)->first();
        } else {
            $role = new $this->modelRole;
        }

        $role->name = $request->name;
        $role->description = $request->description;
        $role->active = true;
        $role->save();
        return response()->redirectToRoute('acl-permissions.roles.list');
    }

    public function roleActive(Request $request)
    {
        $role = $this->modelRole->find($request->id);
        if ($role) {
            $role->active = !$role->active;
            $role->save();
        }
        return response()->redirectToRoute('acl-permissions.roles.list');
    }

    public function roleEdit(Request $request)
    {
        $role = $this->modelRole->find($request->roleId);
        return view('acl-permissions.role-create', ['role' => $role]);
    }

    public function rolePermissions(Request $request, $roleId)
    {
        $role = $this->modelRole->find($roleId);
        $permissions = Permission::get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('acl-permissions.role-x-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function rolePermissionsStore(Request $request)
    {
        $role = $this->modelRole->find($request->role_id);
        $role->permissions()->sync($request->permissions);
        return response()->redirectToRoute('acl-permissions.roles.list');
    }


    /**
     * Get all routes
     *
     * @return array
     */
    private function getRouteList(): array
    {
        $routes = Route::getRoutes();
        $routeNames = [];

        foreach ($routes as $route) {
            if ($route->getName()) {
                $name = $route->getName();
                $name_arr = explode('.', $name);
                switch (count($name_arr)) {
                    case 1:
                        $routeNames['system']['default'][$name] = 1;
                        break;
                    case 2:
                        $routeNames['system'][$name_arr[0]][$name_arr[1]] = 1;
                        break;
                    case 3:
                        $routeNames[$name_arr[0]][$name_arr[1]][$name_arr[2]] = 1;
                        break;
                }
            }
        }

        return $routeNames;
    }
}
