<?php
// modules/EventShiftAssignment/Application/Command/CreateShiftAssignment/CreateShiftAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Application\Command\CreateShiftAssignment;

final readonly class CreateShiftAssignmentCommand
{
    public function __construct(
        public string $participationId,
        public string $shiftId,
    ) {
    }
}
