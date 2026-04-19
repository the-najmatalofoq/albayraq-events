<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Modules\EventContract\Domain\Events\ContractSent;
use Modules\Notification\Application\Listeners\SendContractSentNotification;
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

        // fix: what is the usage of the Broadcast::routes();
        Broadcast::routes();
        require __DIR__ . '/../Broadcasting/channels.php';

        $this->registerEventListeners();
    }

    private function registerEventListeners(): void
    {
        $events = [
            ContractSent::class => [
                SendContractSentNotification::class,
            ],
            \Modules\IAM\Domain\Event\UserLoggedIntoNewDevice::class => [
                \Modules\Notification\Infrastructure\Listeners\SendSessionInvalidatedNotification::class,
            ],
        ];

        foreach ($events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $this->app['events']->listen($event, $listener);
            }
        }
    }
}
