<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'student') {
            Auth::logout();
            return redirect()->route('student.login')
                ->withErrors(['access' => 'Access denied. Students only.']);
        }

        return $next($request);
    }
}