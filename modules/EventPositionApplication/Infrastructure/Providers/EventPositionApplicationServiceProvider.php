<?php
// filePath: modules/EventPositionApplication/Infrastructure/Providers/EventPositionApplicationServiceProvider.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent\EloquentEventPositionApplicationRepository;

final class EventPositionApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventPositionApplicationRepositoryInterface::class, EloquentEventPositionApplicationRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'event-position-application');

        Route::prefix('api/v1/crm/event-position-applications')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/Crm/api.php');
    }
}
