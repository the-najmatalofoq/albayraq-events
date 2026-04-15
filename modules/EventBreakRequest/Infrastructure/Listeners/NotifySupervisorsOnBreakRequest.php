<?php
// modules/EventBreakRequest/Infrastructure/Listeners/NotifySupervisorsOnBreakRequest.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Listeners;

use Modules\EventBreakRequest\Application\Event\BreakRequestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifySupervisorsOnBreakRequest implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BreakRequestCreated $event): void
    {
        // Integration with Notification Module to notify event supervisors
        // For example: identifying users with 'approve_break_requests' capability for this event
        // and dispatching a platform notification or mail.
        Log::info("NotifySupervisorsOnBreakRequest triggered for BreakRequest: {$event->breakRequestId}");
    }
}
