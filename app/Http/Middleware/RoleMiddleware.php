<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    $userRole = strtolower(trim(auth()->user()->role));

    $roles = array_map(function ($r) {
        return strtolower(trim($r));
    }, $roles);

    if (!in_array($userRole, $roles)) {
        abort(403, 'Bạn không có quyền truy cập');
    }

    return $next($request);
}
}