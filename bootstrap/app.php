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
        $middleware->trustProxies(at: '*');
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
