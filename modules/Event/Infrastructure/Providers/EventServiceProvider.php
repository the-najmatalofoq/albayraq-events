<?php
// modules/Event/Infrastructure/Providers/EventServiceProvider.php
declare(strict_types=1);

namespace Modules\Event\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\Event\Infrastructure\Persistence\Eloquent\EloquentEventRepository;

final class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class, EloquentEventRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/events')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
