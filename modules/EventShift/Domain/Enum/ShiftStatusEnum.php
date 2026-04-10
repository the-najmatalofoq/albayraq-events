<?php
// modules/EventShift/Domain/Enum/ShiftStatusEnum.php
declare(strict_types=1);

namespace Modules\EventShift\Domain\Enum;

enum ShiftStatusEnum: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
}
