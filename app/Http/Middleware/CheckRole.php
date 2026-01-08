<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has one of the required roles
        // Jika tidak punya akses, redirect ke dashboard dengan pesan
        if (! in_array($user->role, $roles)) {
            return redirect()->route('dashboard')->with('error', '🔒 Anda tidak memiliki akses ke halaman tersebut. Menu ini khusus untuk '.implode('/', array_map('ucfirst', $roles)).'.');
        }

        return $next($request);
    }
}
