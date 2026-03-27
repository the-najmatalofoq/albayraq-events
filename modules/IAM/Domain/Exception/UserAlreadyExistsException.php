<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;

use Modules\Shared\Domain\Enum\ErrorCode;

final class UserAlreadyExistsException extends DomainException
{
    public static function withEmail(string $email): self
    {
        return new self("A user with email '{$email}' already exists.");
    }

    public static function withPhone(string $phone): self
    {
        return new self("A user with phone '{$phone}' already exists.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::USER_ALREADY_EXISTS;
    }
}

