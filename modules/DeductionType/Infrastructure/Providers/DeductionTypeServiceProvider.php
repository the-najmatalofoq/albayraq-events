<?php
// modules/DeductionType/Infrastructure/Providers/DeductionTypeServiceProvider.php
declare(strict_types=1);

namespace Modules\DeductionType\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;
use Modules\DeductionType\Infrastructure\Persistence\Eloquent\EloquentDeductionTypeRepository;

final class DeductionTypeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DeductionTypeRepositoryInterface::class, EloquentDeductionTypeRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/dashboard/deduction-types')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
