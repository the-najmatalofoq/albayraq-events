<?php
// modules/EventPositionApplication/Domain/ValueObject/ApplicationStatus.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Domain\ValueObject;

// todo: use Enum keyword
enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
        };
    }
}
