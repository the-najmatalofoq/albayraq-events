<?php
// modules/EventJoinRequest/Infrastructure/Providers/EventJoinRequestServiceProvider.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Infrastructure\Persistence\Eloquent\EloquentEventJoinRequestRepository;

final class EventJoinRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventJoinRequestRepositoryInterface::class, EloquentEventJoinRequestRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        Route::prefix('api')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
