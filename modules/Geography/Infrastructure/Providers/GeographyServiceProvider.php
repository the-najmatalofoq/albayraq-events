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
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        $sharedMiddleware = ['api', 'auth:api'];
        $basePath = __DIR__ . '/../Routes';

        $entityRoutes = [
            'Country' => 'api/v1/geographies',
            'State' => 'api/v1/geographies',
            'City' => 'api/v1/geographies',
            'Nationality' => 'api/v1/geographies',
        ];

        foreach ($entityRoutes as $entity => $prefix) {
            $routeFile = "{$basePath}/{$entity}/api.php";

            if (!file_exists($routeFile)) {
                continue;
            }

            Route::prefix($prefix)
                ->middleware($sharedMiddleware)
                ->group($routeFile);
        }
    }
}
