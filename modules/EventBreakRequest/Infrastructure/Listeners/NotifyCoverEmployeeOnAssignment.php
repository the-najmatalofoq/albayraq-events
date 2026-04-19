<?php
// modules/EventBreakRequest/Infrastructure/Listeners/NotifyCoverEmployeeOnAssignment.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Listeners;

use Modules\EventBreakRequest\Application\Event\BreakRequestApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyCoverEmployeeOnAssignment implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BreakRequestApproved $event): void
    {
        if ($event->coverEmployeeId) {
            // Integration with Notification Module to notify the cover employee
            Log::info("NotifyCoverEmployeeOnAssignment triggered for Employee: {$event->coverEmployeeId} regarding BreakRequest: {$event->breakRequestId}");
        }
    }
}
