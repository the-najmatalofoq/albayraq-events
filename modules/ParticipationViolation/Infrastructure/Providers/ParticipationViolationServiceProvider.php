<?php
// modules/ParticipationViolation/Infrastructure/Providers/ParticipationViolationServiceProvider.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;
use Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent\EloquentParticipationViolationRepository;

final class ParticipationViolationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ParticipationViolationRepositoryInterface::class, EloquentParticipationViolationRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/participation-violations')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
