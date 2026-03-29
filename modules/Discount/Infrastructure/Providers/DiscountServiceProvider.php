<?php

namespace Modules\Discount\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Discount\Domain\Repository\DiscountRepositoryInterface;
use Modules\Discount\Infrastructure\Persistence\Eloquent\EloquentDiscountRepository;

class DiscountServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            DiscountRepositoryInterface::class,
            EloquentDiscountRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        Route::prefix('api/v1/discounts')
            ->middleware(['api'])
            ->group(__DIR__.'/../Routes/api.php');
    }
}
