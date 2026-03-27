<?php
// modules/EventRoleAssignment/Infrastructure/Providers/EventRoleAssignmentServiceProvider.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EloquentEventRoleAssignmentRepository;

final class EventRoleAssignmentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventRoleAssignmentRepositoryInterface::class, EloquentEventRoleAssignmentRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/event-role-assignments')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
