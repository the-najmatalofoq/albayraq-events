<?php

namespace Modules\EventContract\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class EventContractServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind repository interfaces, CQRS handlers, etc. here
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        // Register routes, event listeners, etc. here
    }
}
