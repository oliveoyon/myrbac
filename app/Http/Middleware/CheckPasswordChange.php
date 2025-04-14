<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->status == 2) {
            // Allow access ONLY to 'users.my-profile' and 'users.change-my-password'
            $allowedRoutes = ['users.my-profile', 'users.change-my-password'];
    
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('users.my-profile');
            }
        }

        return $next($request);
    }
}

