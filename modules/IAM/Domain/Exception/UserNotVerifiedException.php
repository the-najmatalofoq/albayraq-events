<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotVerifiedException extends DomainException
{
    public static function forEmail(): self
    {
        $mainMessage = __('messages.errors.user_not_verified');
        $e = new self($mainMessage);
        $e->messageKey = __('messages.user.account_not_active');
        $e->errors = ['email' => [$mainMessage]];
        return $e;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_NOT_VERIFIED;
    }
}
