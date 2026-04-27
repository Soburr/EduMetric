<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'teacher') {
            Auth::logout();
            return redirect()->route('teacher.login')
                ->withErrors(['access' => 'Access denied. Teachers only.']);
        }

        return $next($request);
    }
}