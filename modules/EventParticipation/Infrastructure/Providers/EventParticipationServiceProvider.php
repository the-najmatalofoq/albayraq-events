<?php
// modules/EventParticipation/Infrastructure/Providers/EventParticipationServiceProvider.php
declare(strict_types=1);

namespace Modules\EventParticipation\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EloquentEventParticipationRepository;

final class EventParticipationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventParticipationRepositoryInterface::class, EloquentEventParticipationRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/event-participations')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
