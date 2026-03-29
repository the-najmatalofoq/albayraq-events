<?php

declare(strict_types=1);

namespace Modules\EmployeeQuizAttempt\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\EmployeeQuizAttempt\Domain\Repository\EmployeeQuizAttemptRepositoryInterface;
use Modules\EmployeeQuizAttempt\Infrastructure\Persistence\Eloquent\EloquentEmployeeQuizAttemptRepository;

final class EmployeeQuizAttemptServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EmployeeQuizAttemptRepositoryInterface::class,
            EloquentEmployeeQuizAttemptRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        Route::prefix('api/v1/employee-quiz-attempts')
            ->middleware(['api'])
            ->group(__DIR__.'/../Routes/api.php');
    }
}
