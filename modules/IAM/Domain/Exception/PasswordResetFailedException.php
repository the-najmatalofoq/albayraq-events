<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Domain\Exception\DomainException;

final class PasswordResetFailedException extends DomainException
{
    public static function invalidToken(): self
    {
        return new self('Invalid or expired password reset token.');
    }

    public static function userNotFound(): self
    {
        return new self('No user found with this email address.');
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
