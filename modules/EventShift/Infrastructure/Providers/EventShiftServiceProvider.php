<?php
// modules/EventShift/Infrastructure/Providers/EventShiftServiceProvider.php
declare(strict_types=1);

namespace Modules\EventShift\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Infrastructure\Persistence\Eloquent\EloquentEventShiftRepository;

final class EventShiftServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventShiftRepositoryInterface::class, EloquentEventShiftRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        Route::prefix('api')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
