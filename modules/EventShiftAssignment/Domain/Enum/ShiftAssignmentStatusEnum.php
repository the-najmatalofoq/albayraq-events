<?php
// modules/EventShiftAssignment/Domain/Enum/ShiftAssignmentStatusEnum.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Enum;

enum ShiftAssignmentStatusEnum: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
}
