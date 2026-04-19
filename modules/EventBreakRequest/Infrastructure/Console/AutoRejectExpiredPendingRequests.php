<?php
// modules/EventBreakRequest/Infrastructure/Console/AutoRejectExpiredPendingRequests.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Console;

use Illuminate\Console\Command;
use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;
use Modules\EventBreakRequest\Domain\BreakRequestStatus;
use Carbon\Carbon;

class AutoRejectExpiredPendingRequests extends Command
{
    protected $signature = 'break-requests:auto-reject';
    protected $description = 'Automatically reject break requests that have been pending for more than 2 hours';

    public function handle(): void
    {
        // 2 hours threshold
        $threshold = Carbon::now()->subHours(2);

        $expiredRequests = BreakRequestModel::where('status', BreakRequestStatus::PENDING->value)
            ->where('created_at', '<', $threshold)
            ->get();

        $count = $expiredRequests->count();
        if ($count > 0) {
            BreakRequestModel::whereIn('id', $expiredRequests->pluck('id'))
                ->update([
                    'status' => BreakRequestStatus::REJECTED->value,
                    'rejection_reason' => 'Auto-rejected: Request expired after 2 hours.',
                ]);
                
            $this->info("Successfully auto-rejected {$count} expired break requests.");
        } else {
            $this->info('No expired pending break requests found.');
        }
    }
}
