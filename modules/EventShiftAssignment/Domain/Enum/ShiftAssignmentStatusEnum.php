<?php
// modules/EventShiftAssignment/Domain/Enum/ShiftAssignmentStatusEnum.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Enum;

enum ShiftAssignmentStatusEnum: string
{
    case ASSIGNED = 'assigned';
    case COMPLETED = 'completed';
    case MISSED = 'missed';
    case CANCELLED = 'cancelled';
}
