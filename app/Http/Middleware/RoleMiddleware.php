<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek user jika belum login
        if (!request()->user()) {
            return redirect()->route('login')
                ->withErrors(['Silahkan login terlebih dahulu.']);
        }

        $userRole = request()->user()->role?->name;

        // Ubah ke lowercase agar "Admin" dan "admin" sama-sama lolos
        $userRoleLower = strtolower((string)$userRole);
        $rolesLower = array_map('strtolower', $roles);

        // Cek apakah role user sesuai dengan role yang diizinkan
        if (!in_array($userRoleLower, $rolesLower)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
