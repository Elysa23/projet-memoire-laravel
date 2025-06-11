<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsFormateur
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role === 'formateur' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        return redirect()->route('access.denied')->with('error', 'Accès réservé aux formateurs et administrateurs.');
    }
} 