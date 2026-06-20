<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminEmails = config('flora.admin_emails', []);

        if (! auth()->check() || ! in_array(auth()->user()->email, $adminEmails)) {
            abort(403, 'Acesso restrito.');
        }

        return $next($request);
    }
}
