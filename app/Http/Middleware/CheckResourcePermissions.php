<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckResourcePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $actionPermissions = [
            'index' => "view $resource",
            'create' => "create $resource",
            'store' => "create $resource",
            'show' => "view $resource",
            'edit' => "edit $resource",
            'update' => "edit $resource",
            'destroy' => "delete $resource",
        ];
        // dd($actionPermissions,$resource);
        $action = $request->route()->getActionMethod();
        if (isset($actionPermissions[$action]) && ! Auth::user()->can($actionPermissions[$action])) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
