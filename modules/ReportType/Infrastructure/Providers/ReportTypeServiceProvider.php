<?php
// modules/ReportType/Infrastructure/Providers/ReportTypeServiceProvider.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Infrastructure\Persistence\Eloquent\EloquentReportTypeRepository;

final class ReportTypeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReportTypeRepositoryInterface::class, EloquentReportTypeRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/report-types')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
