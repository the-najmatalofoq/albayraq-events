<?php
// modules/EventParticipation/Domain/ValueObject/ParticipationStatusEnum.php
declare(strict_types=1);

namespace Modules\EventParticipation\Domain\ValueObject;

enum ParticipationStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Approval',
            self::ACTIVE => 'Active Participant',
            self::COMPLETED => 'Task Completed',
            self::CANCELLED => 'Participation Cancelled',
        };
    }
}
