<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\User\Domain\ValueObject\Phone;

final class UserAlreadyExistsException extends DomainException
{
    public static function withEmail(string $email): self
    {
        return new self("A user with email '{$email}' already exists.");
    }

    public static function withPhone(Phone $phone): self
    {
        return new self("A user with phone '{$phone->value}' already exists.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_ALREADY_EXISTS;
    }
}

