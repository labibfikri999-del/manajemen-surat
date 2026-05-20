<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $hasAllowedRole = collect($roles)->contains(function (string $role) use ($user) {
            if (method_exists($user, 'hasRole')) {
                return $user->hasRole($role);
            }

            return strtolower(trim((string) $user->role)) === strtolower(trim($role));
        });

        if (! $hasAllowedRole) {
            Log::warning('Role middleware denied request.', [
                'user_id' => $user->id,
                'role' => $user->role,
                'allowed_roles' => $roles,
                'path' => $request->path(),
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                abort(403, 'Anda tidak memiliki akses ke endpoint ini.');
            }

            $redirectRoute = str_starts_with($request->route()?->getName() ?? '', 'kepegawaian.')
                ? 'kepegawaian.dashboard'
                : 'dashboard';

            return redirect()
                ->route($redirectRoute)
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut. Menu ini khusus untuk '.implode('/', array_map('ucfirst', $roles)).'.');
        }

        return $next($request);
    }
}
