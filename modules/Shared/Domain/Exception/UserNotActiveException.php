<?php
// modules/Shared/Domain/Exception/UserNotActiveException.php
declare(strict_types=1);

namespace Modules\Shared\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotActiveException extends DomainException
{
    public static function forUser(string $userId): self
    {
        return new self("User [{$userId}] is not active.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_NOT_ACTIVE;
    }
}
