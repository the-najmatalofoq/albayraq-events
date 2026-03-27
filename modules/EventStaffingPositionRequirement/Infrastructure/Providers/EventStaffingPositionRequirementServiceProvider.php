<?php
// modules/EventStaffingPositionRequirement/Infrastructure/Providers/EventStaffingPositionRequirementServiceProvider.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventStaffingPositionRequirement\Domain\Repository\EventStaffingPositionRequirementRepositoryInterface;
use Modules\EventStaffingPositionRequirement\Infrastructure\Persistence\Eloquent\EloquentEventStaffingPositionRequirementRepository;

final class EventStaffingPositionRequirementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventStaffingPositionRequirementRepositoryInterface::class, EloquentEventStaffingPositionRequirementRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/event-staffing-requirements')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
