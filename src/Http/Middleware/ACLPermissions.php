<?php

namespace LuizFabianoNogueira\AclPermissions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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


        return $next($request);
    }
}
