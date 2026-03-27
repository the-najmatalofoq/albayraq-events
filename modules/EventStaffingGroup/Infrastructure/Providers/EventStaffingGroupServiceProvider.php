<?php
// modules/EventStaffingGroup/Infrastructure/Providers/EventStaffingGroupServiceProvider.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent\EloquentEventStaffingGroupRepository;

final class EventStaffingGroupServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventStaffingGroupRepositoryInterface::class, EloquentEventStaffingGroupRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/event-staffing-groups')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
