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
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check if module_access is null (backward compatibility) or contains the required module
        // We assume flexible access: if 'module_access' is null, they might have access to 'surat' by default
        // But for strict security, we should check explicitly.
        
        $access = $user->module_access ?? ['surat']; // Default to surat if null

        if (!in_array($module, $access)) {
            // Log them out if they are logged in but shouldn't be here? 
            // Better: just deny access or redirect to their allowed dashboard.
            
            // If they are trying to access a page they don't have access to:
            abort(403, 'Akses Ditolak. Akun Anda tidak terdaftar untuk modul ini.');
        }

        return $next($request);
    }
}
