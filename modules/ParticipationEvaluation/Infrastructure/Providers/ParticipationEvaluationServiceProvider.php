<?php
// modules/ParticipationEvaluation/Infrastructure/Providers/ParticipationEvaluationServiceProvider.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\ParticipationEvaluation\Domain\Repository\ParticipationEvaluationRepositoryInterface;
use Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent\EloquentParticipationEvaluationRepository;

final class ParticipationEvaluationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ParticipationEvaluationRepositoryInterface::class, EloquentParticipationEvaluationRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        
        Route::prefix('api/v1/participation-evaluations')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
