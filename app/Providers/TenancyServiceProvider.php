<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenancyServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Global middleware for tenant identification
        $this->app['router']->aliasMiddleware('tenancy', InitializeTenancyByDomain::class);
        $this->app['router']->aliasMiddleware('prevent-access-from-central-domains', PreventAccessFromCentralDomains::class);
    }
}
