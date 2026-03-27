<?php
// modules/EventTask/Domain/ValueObject/TaskStatus.php
declare(strict_types=1);

namespace Modules\EventTask\Domain\ValueObject;

enum TaskStatus: string
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
            self::DONE => 'Done',
            self::CANCELLED => 'Cancelled',
        };
    }
}
