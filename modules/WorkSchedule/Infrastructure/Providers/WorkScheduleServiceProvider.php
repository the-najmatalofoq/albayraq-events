<?php
// modules/WorkSchedule/Infrastructure/Providers/WorkScheduleServiceProvider.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Route;
use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\WorkSchedule\Infrastructure\Persistence\Eloquent\EloquentWorkScheduleRepository;

final class WorkScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WorkScheduleRepositoryInterface::class, EloquentWorkScheduleRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/work-schedules')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
