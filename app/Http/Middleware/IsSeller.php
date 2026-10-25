<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = strtolower(Auth::user()->role);

            if ($role === 'admin' || $role === 'seller') {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak. Halaman ini khusus Penjual.');
    }
}
