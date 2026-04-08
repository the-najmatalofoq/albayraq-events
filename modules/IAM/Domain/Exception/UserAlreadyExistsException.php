<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\User\Domain\ValueObject\Phone;

final class UserAlreadyExistsException extends DomainException
{
    public static function withEmail(): self
    {
        $mainMessage = __('messages.errors.user_already_exists');
        $e = new self($mainMessage);
        $e->messageKey = __('messages.user.account_not_active');
        $e->errors = ['email' => [$mainMessage]];
        return $e;
    }

    public static function withPhone(): self
    {
        $mainMessage = __('messages.errors.user_already_exists');
        $e = new self($mainMessage);
        $e->errors = empty($errors) ? ['phone' => [$mainMessage]] : $errors;
        return $e;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_ALREADY_EXISTS;
    }
}
