<?php

declare(strict_types=1);

namespace Modules\User\Domain\Enum;

enum JoinRequestStatusEnum: string
{
    case Pending = 'pending';
    case Approved='approved';
    case Rejected='rejected';

    public function isApproved(): bool
    {
        return $this === self::Approved;
    }

    public function isRejected(): bool
    {
        return $this === self::Rejected;
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    // public function toggle(): self
    // {
    //     return $this === self::Active ? self::Pending : self::Active;
    // }
}
