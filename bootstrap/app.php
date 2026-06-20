<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Railway termina SSL no load balancer e repassa HTTP para o app.
        // '*' é seguro no Railway — o LB filtra X-Forwarded-For externo antes de chegar aqui.
        $middleware->trustProxies(
            at: env('TRUSTED_PROXIES', '*'),
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO,
        );
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        // Roda em todo request web — interpreta token _adm_preview antes do auth
        $middleware->web(append: [
            \App\Http\Middleware\AdminPreviewMiddleware::class,
        ]);
        $middleware->alias([
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
            'nickname' => \App\Http\Middleware\RequireNickname::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
