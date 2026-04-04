<?php

declare(strict_types=1);

namespace Modules\User\Domain\Enum;

enum JoinRequestStatusEnum: string
{
    case Pending = 'pending';
    case Active = 'active';

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function toggle(): self
    {
        return $this === self::Active ? self::Pending : self::Active;
    }
}
