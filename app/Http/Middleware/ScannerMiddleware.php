<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScannerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'scanner'])) {
            abort(403, 'Accès réservé aux scanners autorisés.');
        }
        return $next($request);
    }
}
