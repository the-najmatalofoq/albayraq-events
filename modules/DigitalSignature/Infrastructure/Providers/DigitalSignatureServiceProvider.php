<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Infrastructure\Persistence\Eloquent\EloquentDigitalSignatureRepository;

final class DigitalSignatureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            DigitalSignatureRepositoryInterface::class,
            EloquentDigitalSignatureRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api/v1/digital-signatures')
            ->middleware(['api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
