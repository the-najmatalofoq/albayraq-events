<?php
// modules/ViolationType/Infrastructure/Providers/ViolationTypeServiceProvider.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\EloquentViolationTypeRepository;

final class ViolationTypeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ViolationTypeRepositoryInterface::class, EloquentViolationTypeRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/violation-types')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
