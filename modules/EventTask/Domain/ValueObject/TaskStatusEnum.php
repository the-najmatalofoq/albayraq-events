<?php
// modules/EventTask/Domain/ValueObject/TaskStatusEnum.php
declare(strict_types=1);

namespace Modules\EventTask\Domain\ValueObject;

enum TaskStatusEnum: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'In Progress',
            self::DONE => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}
