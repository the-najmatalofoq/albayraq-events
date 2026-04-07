<?php
// modules/IAM/Infrastructure/Providers/IAMServiceProvider.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Infrastructure\Services\BcryptPasswordHasher;
use Modules\IAM\Infrastructure\Services\JwtTokenManager;

use Illuminate\Support\Facades\Event;
use Modules\IAM\Domain\Repository\OtpCodeRepositoryInterface;
use Modules\IAM\Domain\Service\OtpGeneratorInterface;
use Modules\IAM\Infrastructure\Persistence\Eloquent\Repositories\EloquentOtpCodeRepository;
use Modules\IAM\Infrastructure\Services\RandomOtpGenerator;
use Modules\IAM\Domain\Event\OtpRequested;
use Modules\IAM\Infrastructure\Listeners\LogOtpNotification;

final class IAMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TokenManager::class, JwtTokenManager::class);
        $this->app->bind(PasswordHasher::class, BcryptPasswordHasher::class);
        $this->app->bind(OtpCodeRepositoryInterface::class, EloquentOtpCodeRepository::class);
        $this->app->bind(OtpGeneratorInterface::class, RandomOtpGenerator::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Event::listen(
            OtpRequested::class,
            LogOtpNotification::class,
        );

        Route::prefix('api/v1/auth')
            ->middleware(['api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
