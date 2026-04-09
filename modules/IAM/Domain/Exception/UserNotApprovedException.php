<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotApprovedException extends DomainException
{
    public static function forUser(string $messageKey, string $mainMessage): self
    {
        $e = new self($mainMessage);
        $e->messageKey = $messageKey;
        $e->errors = [$mainMessage];
        return $e;
    }
    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_NOT_APPROVED;
    }
}
