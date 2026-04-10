<?php
// modules/EventShiftAssignment/Infrastructure/Providers/EventShiftAssignmentServiceProvider.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventShiftAssignment\Domain\Repository\EventShiftAssignmentRepositoryInterface;
use Modules\EventShiftAssignment\Infrastructure\Persistence\Eloquent\EloquentEventShiftAssignmentRepository;

final class EventShiftAssignmentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventShiftAssignmentRepositoryInterface::class, EloquentEventShiftAssignmentRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
