<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;  

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!in_array(Auth::user()->rol, $roles)) {
            abort(403, 'No tienes permiso para acceder aquí.');
        }

        return $next($request);
    }
}