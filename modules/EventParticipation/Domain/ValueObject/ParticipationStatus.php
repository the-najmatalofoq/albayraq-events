<?php
// modules/EventParticipation/Domain/ValueObject/ParticipationStatus.php
declare(strict_types=1);

namespace Modules\EventParticipation\Domain\ValueObject;

enum ParticipationStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::SUSPENDED => 'Suspended',
        };
    }
}
