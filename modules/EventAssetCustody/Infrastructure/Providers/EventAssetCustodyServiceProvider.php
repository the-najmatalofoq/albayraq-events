<?php
// modules/EventAssetCustody/Infrastructure/Providers/EventAssetCustodyServiceProvider.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventAssetCustody\Domain\Repository\EventAssetCustodyRepositoryInterface;
use Modules\EventAssetCustody\Infrastructure\Persistence\Eloquent\EloquentEventAssetCustodyRepository;

final class EventAssetCustodyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventAssetCustodyRepositoryInterface::class, EloquentEventAssetCustodyRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/event-asset-custody')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
