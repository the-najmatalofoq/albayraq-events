<?php
// modules/EventRoleCapability/Infrastructure/Providers/EventRoleCapabilityServiceProvider.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent\EloquentEventRoleCapabilityRepository;

final class EventRoleCapabilityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventRoleCapabilityRepositoryInterface::class, EloquentEventRoleCapabilityRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/event-role-capabilities')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
