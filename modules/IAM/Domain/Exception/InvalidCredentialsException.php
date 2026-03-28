<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class InvalidCredentialsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials.');
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::INVALID_CREDENTIALS;
    }
}
