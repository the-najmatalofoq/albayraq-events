<?php
// modules/EventStaffingPosition/Infrastructure/Providers/EventStaffingPositionServiceProvider.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EloquentEventStaffingPositionRepository;

final class EventStaffingPositionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventStaffingPositionRepositoryInterface::class, EloquentEventStaffingPositionRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/event-staffing-positions')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
