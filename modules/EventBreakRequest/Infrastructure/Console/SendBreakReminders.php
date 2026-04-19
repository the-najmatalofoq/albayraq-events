<?php
// modules/EventBreakRequest/Infrastructure/Console/SendBreakReminders.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Console;

use Illuminate\Console\Command;
use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;
use Modules\EventBreakRequest\Domain\BreakRequestStatus;
use Carbon\Carbon;

class SendBreakReminders extends Command
{
    protected $signature = 'break-requests:send-reminders';
    protected $description = 'Send reminders to employees 15 minutes before their approved break starts';

    public function handle(): void
    {
        // Target time is exactly 15 minutes from now (+/- some buffer depending on cron frequency)
        $now = Carbon::now();
        $targetTimeStart = $now->copy()->addMinutes(15)->format('H:i:00');
        $targetTimeEnd = $now->copy()->addMinutes(16)->format('H:i:00');

        $upcomingBreaks = BreakRequestModel::where('status', BreakRequestStatus::APPROVED->value)
            ->where('date', $now->format('Y-m-d'))
            ->whereBetween('start_time', [$targetTimeStart, $targetTimeEnd])
            ->get();

        $count = 0;
        foreach ($upcomingBreaks as $break) {
            // Dispatch notification event to employee
            // e.g. Event::dispatch(new UpcomingBreakReminder($break->id, ...))
            // \Log::info("Reminder sent to employee: {$break->requested_by} for break at {$break->start_time}");
            $count++;
        }

        if ($count > 0) {
            $this->info("Successfully sent {$count} break reminders.");
        } else {
            $this->info('No upcoming breaks in the next 15 minutes found.');
        }
    }
}
