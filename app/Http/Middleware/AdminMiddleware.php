<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['access' => 'Access denied. Administrators only.']);
        }

        return $next($request);
    }
}