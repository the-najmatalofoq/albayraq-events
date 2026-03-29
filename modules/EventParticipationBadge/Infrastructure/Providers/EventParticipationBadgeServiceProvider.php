<?php

namespace Modules\EventParticipationBadge\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\EventParticipationBadge\Domain\Repository\EventParticipationBadgeRepositoryInterface;
use Modules\EventParticipationBadge\Infrastructure\Persistence\Eloquent\EloquentEventParticipationBadgeRepository;

class EventParticipationBadgeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EventParticipationBadgeRepositoryInterface::class,
            EloquentEventParticipationBadgeRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        Route::prefix('api/v1/event-participation-badges')
            ->middleware(['api'])
            ->group(__DIR__.'/../Routes/api.php');
    }
}
