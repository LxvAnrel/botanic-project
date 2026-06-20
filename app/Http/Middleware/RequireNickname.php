<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireNickname
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && ! auth()->user()->nickname) {
            if (! $request->routeIs('nickname.*')) {
                return redirect()->route('nickname.escolher');
            }
        }

        return $next($request);
    }
}
