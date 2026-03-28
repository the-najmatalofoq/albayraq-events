<?php
// modules/User/Infrastructure/Providers/UserServiceProvider.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\User\Domain\Repository\UserProfileRepositoryInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Infrastructure\Persistence\Eloquent\EloquentUserProfileRepository;
use Modules\User\Infrastructure\Persistence\Eloquent\EloquentUserRepository;

final class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserProfileRepositoryInterface::class, EloquentUserProfileRepository::class);
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/user-profiles')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
