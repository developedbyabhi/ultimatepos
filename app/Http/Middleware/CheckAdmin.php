<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Check if user has superadmin permission or Admin role for their business
        if (!$user->can('superadmin') && !$user->hasRole('Admin#' . $user->business_id)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}