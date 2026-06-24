<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND their role is 'student'
        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request); // Let them pass
        }

        // If they fail the check, kick them back to the login page
        return redirect('/login')->with('error', 'You do not have student access.');
    }
}
