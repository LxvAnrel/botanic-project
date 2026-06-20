<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminEmails = array_filter(array_map('trim', explode(',', env('ADMIN_EMAIL', ''))));

        if (! auth()->check() || ! in_array(auth()->user()->email, $adminEmails)) {
            abort(403, 'Acesso restrito.');
        }

        return $next($request);
    }
}
