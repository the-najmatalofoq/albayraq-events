<?php
// modules/EventBreakRequest/Application/Events/BreakRequestCreated.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Event;

final readonly class BreakRequestCreated
{
    public function __construct(
        public string $breakRequestId,
        public string $eventId,
        public string $participationId
    ) {}
}
