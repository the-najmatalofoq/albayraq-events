<?php
// modules/EventBreakRequest/Infrastructure/Listeners/NotifyEmployeeOnApproval.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Listeners;

use Modules\EventBreakRequest\Application\Event\BreakRequestApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyEmployeeOnApproval implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BreakRequestApproved $event): void
    {
        // Integration with Notification Module to notify the requesting employee
        Log::info("NotifyEmployeeOnApproval triggered for BreakRequest: {$event->breakRequestId}");
    }
}
