<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai Super Admin terlebih dahulu.');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('customer.home')->with('error', 'Akses ditolak! Anda tidak memiliki hak akses Super Admin.');
        }

        return $next($request);
    }
}
