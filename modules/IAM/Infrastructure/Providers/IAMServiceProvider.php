<?php
// modules/IAM/Infrastructure/Providers/IAMServiceProvider.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\IAM\Infrastructure\Services\BcryptPasswordHasher;
use Modules\IAM\Infrastructure\Services\JwtTokenManager;

final class IAMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TokenManagerInterface::class, JwtTokenManager::class);
        $this->app->bind(PasswordHasher::class, BcryptPasswordHasher::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1')
            ->middleware(['api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
