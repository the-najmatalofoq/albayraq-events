<?php

declare(strict_types=1);

namespace Modules\HealthModule\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\HealthModule\Application\Health\CheckApplicationHealthHandler;
use Modules\HealthModule\Domain\Service\HealthCheckerInterface;
use Modules\HealthModule\Infrastructure\Service\LaravelHealthChecker;

final class HealthModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HealthCheckerInterface::class, LaravelHealthChecker::class);
        $this->app->singleton(CheckApplicationHealthHandler::class);
    }

    public function boot(): void
    {
        if (is_dir(__DIR__ . '/../Persistence/Migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        }

        Route::prefix('api/v1/health')
            ->middleware(['api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
