<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is connected and is active
        if(auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            return redirect('/')->withErrors(['active' => 'Votre compte est désactivé.']);
        }
        
        // If the user is connected
        if (!auth()->check()) {
            return redirect(route('login'));
        }
    
        return $next($request);
    }
}
