<?php
// modules/EventBreakRequest/Infrastructure/Listeners/SendPushNotificationListener.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendPushNotificationListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(object $event): void
    {
        // This is a generic listener that can tap into multiple domain events to send FCM push notifications.
        // Integration with Notification Module for Firebase Cloud Messaging
        Log::info("SendPushNotificationListener triggered for Event: " . get_class($event));
    }
}
