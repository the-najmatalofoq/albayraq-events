<?php

declare(strict_types=1);

namespace Modules\Quiz\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Infrastructure\Persistence\Eloquent\EloquentQuizRepository;

final class QuizServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            QuizRepositoryInterface::class,
            EloquentQuizRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        Route::prefix('api/v1/events')
            ->middleware(['api'])
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
