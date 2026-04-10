<?php
// modules/EventStaffingPosition/Application/Command/UpdatePosition/UpdatePositionCommand.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Application\Command\UpdatePosition;

final readonly class UpdatePositionCommand
{
    public function __construct(
        public string $id,
        public array $title,
        public float $wageAmount,
        public string $wageType,
        public int $headcount,
        public array $requirements,
    ) {
    }
}
