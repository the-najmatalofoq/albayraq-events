<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotFoundException extends DomainException
{
    public static function withEmail(string $email): self
    {
        return new self("User with email '{$email}' not found.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}

