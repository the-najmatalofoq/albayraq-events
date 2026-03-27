<?php
// modules/EventOperationalReport/Infrastructure/Providers/EventOperationalReportServiceProvider.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventOperationalReport\Domain\Repository\EventOperationalReportRepositoryInterface;
use Modules\EventOperationalReport\Infrastructure\Persistence\Eloquent\EloquentEventOperationalReportRepository;

final class EventOperationalReportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventOperationalReportRepositoryInterface::class, EloquentEventOperationalReportRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/event-operational-reports')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
