<?php

namespace LuizFabianoNogueira\AclPermissions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LuizFabianoNogueira\AclPermissions\Services\AclPermissionService;
use Illuminate\Support\Facades\Gate;

class ACLPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Gate::check($request->route()->getName())) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return abort(403, 'Unauthorized');
    }
}
