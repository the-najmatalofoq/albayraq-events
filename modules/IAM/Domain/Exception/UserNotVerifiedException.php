<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotVerifiedException extends DomainException
{
    public static function forEmail(string $messageKey, string $mainMessage): self
    {
        $e = new self($mainMessage);
        $e->messageKey = $messageKey;
        $e->errors = ['email' => [$mainMessage]];
        return $e;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_NOT_VERIFIED;
    }
}
