<?php
// modules/EventBreakRequest/Application/Events/BreakRequestApproved.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Event;

final readonly class BreakRequestApproved
{
    public function __construct(
        public string $breakRequestId,
        public string $participationId,
        public ?string $coverEmployeeId
    ) {}
}
