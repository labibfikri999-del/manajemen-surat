<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if (empty($modules)) {
            $modules = ['surat'];
        }

        $access = $this->resolveModuleAccess($user->module_access);

        if (empty(array_intersect($modules, $access))) {
            // Log them out if they are logged in but shouldn't be here? 
            // Better: just deny access or redirect to their allowed dashboard.
            
            // If they are trying to access a page they don't have access to:
            abort(403, 'Akses Ditolak. Akun Anda tidak terdaftar untuk modul ini.');
        }

        return $next($request);
    }

    private function resolveModuleAccess(mixed $moduleAccess): array
    {
        if (is_string($moduleAccess)) {
            $decoded = json_decode($moduleAccess, true);
            $moduleAccess = is_array($decoded)
                ? $decoded
                : array_filter(array_map('trim', explode(',', $moduleAccess)));
        }

        if (! is_array($moduleAccess) || empty($moduleAccess)) {
            return ['surat'];
        }

        return $moduleAccess;
    }
}
