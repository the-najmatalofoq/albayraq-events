<?php
// modules/EventAttendance/Infrastructure/Providers/EventAttendanceServiceProvider.php
declare(strict_types=1);

namespace Modules\EventAttendance\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventAttendance\Domain\Repository\EventAttendanceRepositoryInterface;
use Modules\EventAttendance\Infrastructure\Persistence\Eloquent\EloquentEventAttendanceRepository;

final class EventAttendanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventAttendanceRepositoryInterface::class, EloquentEventAttendanceRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/event-attendance')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
