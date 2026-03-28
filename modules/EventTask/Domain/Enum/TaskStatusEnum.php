<?php
// modules/EventTask/Domain/Enum/TaskStatusEnum.php
declare(strict_types=1);

namespace Modules\EventTask\Domain\Enum;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';
}
