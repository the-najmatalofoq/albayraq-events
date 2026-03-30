<?php

declare(strict_types=1);

namespace Modules\EventContract\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventContract\Domain\Repository\EventContractRepositoryInterface;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EloquentEventContractRepository;

final class EventContractServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EventContractRepositoryInterface::class,
            EloquentEventContractRepository::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/event-contracts')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
