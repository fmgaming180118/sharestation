<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsHost
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_host) {
            return redirect('/')->with('error', 'Anda bukan host');
        }

        return $next($request);
    }
}
