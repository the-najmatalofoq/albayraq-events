<?php
// modules/EventJoinRequest/Domain/Enum/JoinRequestStatusEnum.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Enum;

enum JoinRequestStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}
