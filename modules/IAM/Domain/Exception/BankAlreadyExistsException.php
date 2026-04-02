<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class BankAlreadyExistsException extends DomainException
{
    public static function withIban(string $iban): self
    {
        return new self("A bank account with IBAN '{$iban}' already exists.");
    }

    public static function withOwnerAccount(string $ownerAccount): self
    {
        return new self("A bank account with owner account '{$ownerAccount}' already exists.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::BANK_ALREADY_EXISTS;
    }
}
