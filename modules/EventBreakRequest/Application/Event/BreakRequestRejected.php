<?php
// modules/EventBreakRequest/Application/Events/BreakRequestRejected.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Event;

final readonly class BreakRequestRejected
{
    public function __construct(
        public string $breakRequestId,
        public string $participationId,
        public string $rejectionReason
    ) {}
}
