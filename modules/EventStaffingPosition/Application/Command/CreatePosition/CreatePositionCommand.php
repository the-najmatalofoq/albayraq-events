<?php
// modules/EventStaffingPosition/Application/Command/CreatePosition/CreatePositionCommand.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Application\Command\CreatePosition;

final readonly class CreatePositionCommand
{
    public function __construct(
        public string $eventId,
        public array $title,
        public float $wageAmount,
        public string $wageType,
        public int $headcount,
        public array $requirements,
        public bool $isAnnounced = false,
    ) {
    }
}
