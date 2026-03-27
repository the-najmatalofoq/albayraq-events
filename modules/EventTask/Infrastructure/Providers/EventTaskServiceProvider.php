<?php
// modules/EventTask/Infrastructure/Providers/EventTaskServiceProvider.php
declare(strict_types=1);

namespace Modules\EventTask\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventTask\Domain\Repository\EventTaskRepositoryInterface;
use Modules\EventTask\Infrastructure\Persistence\Eloquent\EloquentEventTaskRepository;

final class EventTaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventTaskRepositoryInterface::class, EloquentEventTaskRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/event-tasks')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
