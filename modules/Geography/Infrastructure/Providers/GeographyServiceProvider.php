<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Geography\Domain\Repository\{
    CountryRepositoryInterface,
    NationalityRepositoryInterface,
    StateRepositoryInterface,
    CityRepositoryInterface,
};
use Modules\Geography\Infrastructure\Persistence\Eloquent\Repositories\{
    EloquentCountryRepository,
    EloquentNationalityRepository,
    EloquentStateRepository,
    EloquentCityRepository
};
use Modules\Geography\Application\Service\GeoValidationService;
use Illuminate\Support\Facades\Route;

final class GeographyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CountryRepositoryInterface::class, EloquentCountryRepository::class);
        $this->app->bind(NationalityRepositoryInterface::class, EloquentNationalityRepository::class);
        $this->app->bind(StateRepositoryInterface::class, EloquentStateRepository::class);
        $this->app->bind(CityRepositoryInterface::class, EloquentCityRepository::class);

        $this->app->singleton(GeoValidationService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        Route::prefix('api')
            ->middleware('api')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
