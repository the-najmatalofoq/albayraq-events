<?php

declare(strict_types=1);

namespace Modules\Question\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Infrastructure\Persistence\Eloquent\EloquentQuestionRepository;

final class QuestionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            QuestionRepositoryInterface::class,
            EloquentQuestionRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'question');

        Route::prefix('api/v1/crm/questions')
            ->middleware(['api', 'auth:api'])
            ->group(__DIR__ . '/../Routes/Crm/api.php');
    }
}
