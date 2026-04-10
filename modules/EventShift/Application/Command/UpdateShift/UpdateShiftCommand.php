<?php
// modules/EventShift/Application/Command/UpdateShift/UpdateShiftCommand.php
declare(strict_types=1);

namespace Modules\EventShift\Application\Command\UpdateShift;

final readonly class UpdateShiftCommand
{
    public function __construct(
        public string $shiftId,
        public ?string $label,
        public ?string $startAt,
        public ?string $endAt,
        public ?int $maxAssignees,
    ) {
    }
}
