<?php
// modules/EventBreakRequest/Application/Queries/GetPendingBreakRequestsHandler.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Query;

use Illuminate\Support\Collection;
use Modules\EventBreakRequest\Domain\BreakRequestStatus;
use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;

final readonly class GetPendingBreakRequestsHandler
{
    /** @return Collection */
    public function handle(GetPendingBreakRequestsQuery $query): Collection
    {
        $ormQuery = BreakRequestModel::query()
            ->where('status', BreakRequestStatus::PENDING->value)
            ->whereHas('participation', function ($q) use ($query) {
                $q->where('event_id', $query->eventId);
            })
            ->with(['requestedBy', 'participation']);

        if ($query->date) {
            $ormQuery->where('date', $query->date);
        }

        return $ormQuery->get();
    }
}
