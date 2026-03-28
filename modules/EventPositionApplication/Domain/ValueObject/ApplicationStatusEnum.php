<?php
// modules/EventPositionApplication/Domain/ValueObject/ApplicationStatusEnum.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Domain\ValueObject;

enum ApplicationStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Application Approved',
            self::REJECTED => 'Application Rejected',
            self::CANCELLED => 'Application Cancelled',
        };
    }
}
