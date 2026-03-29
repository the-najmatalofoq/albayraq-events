<?php

namespace Modules\EventExperienceCertificate\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\EventExperienceCertificate\Domain\Repository\EventExperienceCertificateRepositoryInterface;
use Modules\EventExperienceCertificate\Infrastructure\Persistence\Eloquent\EloquentEventExperienceCertificateRepository;

class EventExperienceCertificateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EventExperienceCertificateRepositoryInterface::class,
            EloquentEventExperienceCertificateRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Persistence/Migrations');
        Route::prefix('api/v1/event-experience-certificates')
            ->middleware(['api'])
            ->group(__DIR__.'/../Routes/api.php');
    }
}
