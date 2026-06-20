<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireNickname
{
    public function handle(Request $request, Closure $next)
    {
        // Preview de admin — não forçar escolha de nick para conta do usuário visitado
        if ($request->query('_adm_preview')) {
            return $next($request);
        }

        if (auth()->check() && ! auth()->user()->nickname) {
            if (! $request->routeIs('nickname.*')) {
                return redirect()->route('nickname.escolher');
            }
        }

        return $next($request);
    }
}
