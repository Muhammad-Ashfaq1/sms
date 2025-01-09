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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'central_domain' => \App\Http\Middleware\EnsureCentralDomain::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'tenancy' => \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
            'prevent_access_from_central_domains' => \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
            'tenant.auth' => \App\Http\Middleware\TenantAuthenticate::class,
        ]);

        // Priority for tenancy middleware
        $middleware->priority([
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
