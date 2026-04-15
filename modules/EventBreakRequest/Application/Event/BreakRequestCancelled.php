<?php
// modules/EventBreakRequest/Application/Events/BreakRequestCancelled.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Event;

final readonly class BreakRequestCancelled
{
    public function __construct(
        public string $breakRequestId,
        public string $participationId
    ) {}
}
