<?php
// modules/EventShift/Application/Command/CreateShift/CreateShiftCommand.php
declare(strict_types=1);

namespace Modules\EventShift\Application\Command\CreateShift;

final readonly class CreateShiftCommand
{
    public function __construct(
        public string $eventId,
        public string $positionId,
        public string $label,
        public string $startAt,
        public string $endAt,
        public ?int $maxAssignees = null,
    ) {}
}
