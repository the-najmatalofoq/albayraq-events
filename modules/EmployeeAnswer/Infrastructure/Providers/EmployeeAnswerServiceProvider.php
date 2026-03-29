<?php

declare(strict_types=1);

namespace Modules\EmployeeAnswer\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\EmployeeAnswer\Domain\Repository\EmployeeAnswerRepositoryInterface;
use Modules\EmployeeAnswer\Infrastructure\Persistence\Eloquent\EloquentEmployeeAnswerRepository;

final class EmployeeAnswerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EmployeeAnswerRepositoryInterface::class,
            EloquentEmployeeAnswerRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        Route::prefix('api/v1/employee-answers')
            ->middleware(['api'])
            ->group(__DIR__.'/../Routes/api.php');
    }
}
