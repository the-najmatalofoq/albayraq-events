<?php
// modules/EventBreakRequest/Application/Queries/GetPendingBreakRequestsQuery.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Query;

final readonly class GetPendingBreakRequestsQuery
{
    public function __construct(
        public string $eventId,
        public ?string $date = null
    ) {}
}
