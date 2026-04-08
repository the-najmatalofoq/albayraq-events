<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class BankAlreadyExistsException extends DomainException
{
    public static function withIban(array $errors = []): self
    {
        $mainMessage = __('messages.errors.bank_already_exists');
        $e = new self($mainMessage);
        $e->errors = empty($errors) ? ['iban' => [$mainMessage]] : $errors;
        return $e;
    }

    public static function withOwnerAccount(array $errors = []): self
    {
        $mainMessage = __('messages.errors.bank_already_exists');
        $e = new self($mainMessage);
        $e->errors = empty($errors) ? ['owner_account' => [$mainMessage]] : $errors;
        return $e;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::BANK_ALREADY_EXISTS;
    }
}
