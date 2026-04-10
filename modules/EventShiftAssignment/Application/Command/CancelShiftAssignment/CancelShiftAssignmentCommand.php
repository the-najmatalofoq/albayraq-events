<?php
// modules/EventShiftAssignment/Application/Command/CancelShiftAssignment/CancelShiftAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Application\Command\CancelShiftAssignment;

final readonly class CancelShiftAssignmentCommand
{
    public function __construct(public string $assignmentId)
    {
    }
}
