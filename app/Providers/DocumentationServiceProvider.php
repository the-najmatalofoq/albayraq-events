<?php
// app/Providers/DocumentationServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;
use Dedoc\Scramble\Scramble;

class DocumentationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Scramble::configure()
            ->routes(function (Route $route) {
                return str_starts_with($route->uri(), 'api/');
            });
    }
}
