<?php

namespace Modules\EventExpense\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class EventExpenseServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind repository interfaces, CQRS handlers, etc. here
    }

    public function boot()
    {
        // Register routes, event listeners, etc. here
    }
}
