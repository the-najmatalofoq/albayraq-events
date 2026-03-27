<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\IAM\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Repository\RoleRepository;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use Modules\IAM\Infrastructure\Persistence\Eloquent\EloquentRoleRepository;
use Modules\IAM\Infrastructure\Services\BcryptPasswordHasher;
use Modules\IAM\Infrastructure\Services\JwtTokenManager;

final class IAMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
        $this->app->bind(TokenManager::class, JwtTokenManager::class);
        $this->app->bind(PasswordHasher::class, BcryptPasswordHasher::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
        // todo: delete, we don't have "web" in our project, is Api-based.
        Route::middleware('web')
            ->group(__DIR__ . '/../Routes/web.php');
    }
}
