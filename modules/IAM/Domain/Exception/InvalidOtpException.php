<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Domain\Exception\DomainException;

final class InvalidOtpException extends DomainException
{
    public static function expired(): self
    {
        return new self('OTP code has expired.');
    }

    public static function invalid(): self
    {
        return new self('OTP code is invalid.');
    }

    public static function alreadyVerified(): self
    {
        return new self('OTP code has already been verified.');
    }

    public static function tooManyAttempts(): self
    {
        return new self('Too many OTP requests. Please wait before requesting a new code.');
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
