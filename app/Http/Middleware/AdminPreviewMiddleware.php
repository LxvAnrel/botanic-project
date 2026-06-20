<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminPreviewMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('_adm_preview') ?? $request->input('_adm_preview');

        if (! $token) {
            return $next($request);
        }

        $data = Cache::get("adm_preview_{$token}");

        if (! $data) {
            // Token expirado ou inválido — continua normalmente
            return $next($request);
        }

        // Autentica como o usuário alvo sem gravar na sessão e sem disparar
        // o evento Login (onceUsingId usa setUser internamente)
        auth()->onceUsingId($data['user_id']);

        // Compartilha com todas as views para a barra flutuante
        view()->share('adminPreview', [
            'token'      => $token,
            'admin_id'   => $data['admin_id'],
            'user_id'    => $data['user_id'],
            'user_name'  => $data['user_name'] ?? '',
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return $next($request);
    }
}
