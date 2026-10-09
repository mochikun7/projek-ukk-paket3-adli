<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        
        if (session('role') != $role) {
            return redirect()->route('login')->with('error', 'Akses ditolak! Silakan login dulu.');
        }

        return $next($request);
    }
}