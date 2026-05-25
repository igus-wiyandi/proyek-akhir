<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoggedInAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('loginAdmin');
        }
        return $next($request);
    }
}
