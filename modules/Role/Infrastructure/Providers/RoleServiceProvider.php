<?php
// modules/Role/Infrastructure/Providers/RoleServiceProvider.php
declare(strict_types=1);

namespace Modules\Role\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Infrastructure\Persistence\Eloquent\EloquentRoleRepository;

final class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/dashboard/roles')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
