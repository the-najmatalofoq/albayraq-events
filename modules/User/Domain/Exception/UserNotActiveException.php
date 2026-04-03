<?php

declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotActiveException extends DomainException
{
    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_NOT_ACTIVE;
    }

    public static function forUser(string $userId): self
    {
        return new self("User [{$userId}] is not active.");
    }
}
