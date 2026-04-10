<?php
// modules/EventShiftAssignment/Application/Command/AssignToShift/AssignToShiftCommand.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Application\Command\AssignToShift;

final readonly class AssignToShiftCommand
{
    public function __construct(
        public string $shiftId,
        public string $participationId,
        public string $assignedBy,
        public ?string $notes = null,
    ) {}
}
