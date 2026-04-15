<?php
// modules/EventBreakRequest/Infrastructure/Providers/EventBreakRequestServiceProvider.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\EventBreakRequest\Domain\Repository\BreakRequestRepositoryInterface;
use Modules\EventBreakRequest\Infrastructure\Persistence\Repositories\EloquentBreakRequestRepository;
use Modules\EventBreakRequest\Infrastructure\Console\AutoRejectExpiredPendingRequests;
use Modules\EventBreakRequest\Infrastructure\Console\SendBreakReminders;
use Illuminate\Support\Facades\Event;
use Modules\EventBreakRequest\Application\Event\{
    BreakRequestCreated,
    BreakRequestApproved,
    BreakRequestRejected,
    BreakRequestCancelled
};
use Modules\EventBreakRequest\Infrastructure\Listeners\{
    NotifySupervisorsOnBreakRequest,
    NotifyEmployeeOnApproval,
    NotifyCoverEmployeeOnAssignment,
    SendPushNotificationListener
};

class EventBreakRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BreakRequestRepositoryInterface::class, EloquentBreakRequestRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Persistence/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../../Lang', 'break_requests');

        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api')
            ->group(__DIR__ . '/../Routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AutoRejectExpiredPendingRequests::class,
                SendBreakReminders::class,
            ]);
        }

        // Register Event Listeners
        Event::listen(BreakRequestCreated::class, NotifySupervisorsOnBreakRequest::class);
        Event::listen(BreakRequestCreated::class, SendPushNotificationListener::class);
        
        Event::listen(BreakRequestApproved::class, NotifyEmployeeOnApproval::class);
        Event::listen(BreakRequestApproved::class, NotifyCoverEmployeeOnAssignment::class);
        Event::listen(BreakRequestApproved::class, SendPushNotificationListener::class);
        
        Event::listen(BreakRequestRejected::class, SendPushNotificationListener::class);
        Event::listen(BreakRequestCancelled::class, SendPushNotificationListener::class);
    }
}
