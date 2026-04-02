<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Modules\Notification\Domain\Repository\DeviceTokenRepositoryInterface;
use Modules\Notification\Infrastructure\Persistence\Eloquent\EloquentDeviceTokenRepository;

final class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            DeviceTokenRepositoryInterface::class,
            EloquentDeviceTokenRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');

        Broadcast::routes();
        require __DIR__ . '/../Broadcasting/channels.php';

        $this->registerEventListeners();
    }

    private function registerEventListeners(): void
    {
        $events = [];

        foreach ($events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $this->app['events']->listen($event, $listener);
            }
        }
    }
}
