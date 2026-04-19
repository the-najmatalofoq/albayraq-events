<?php
// modules/PenaltyType/Infrastructure/Providers/PenaltyTypeServiceProvider.php
declare(strict_types=1);

namespace Modules\PenaltyType\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;
use Modules\PenaltyType\Infrastructure\Persistence\Eloquent\EloquentPenaltyTypeRepository;

final class PenaltyTypeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PenaltyTypeRepositoryInterface::class, EloquentPenaltyTypeRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/dashboard/penalty-types')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
