<?php
// modules/Wage/Infrastructure/Providers/WageServiceProvider.php
declare(strict_types=1);

namespace Modules\Wage\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Wage\Infrastructure\Persistence\Eloquent\EloquentWageRepository;

final class WageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WageRepositoryInterface::class, EloquentWageRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/wages')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
