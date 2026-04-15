<?php
// modules/Currency/Infrastructure/Providers/CurrencyServiceProvider.php
declare(strict_types=1);

namespace Modules\Currency\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Currency\Infrastructure\Persistence\Eloquent\EloquentCurrencyRepository;
use Illuminate\Support\Facades\Route;

final class CurrencyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CurrencyRepositoryInterface::class,
            EloquentCurrencyRepository::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        $this->mapApiRoutes();
    }

    private function mapApiRoutes(): void
    {
        Route::middleware(['api', 'auth:api'])
            ->prefix('api/v1/dashboard/currencies')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
