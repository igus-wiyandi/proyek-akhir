<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginCheckGuru
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'guru') {
            return redirect()->route('logoutGuru');
        }
        return $next($request);
    }
}
