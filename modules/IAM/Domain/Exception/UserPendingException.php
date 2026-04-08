<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserPendingException extends DomainException
{
    public static function create(): self
    {
        $mainMessage = __('messages.errors.user_pending');
        $e = new self($mainMessage);
        $e->messageKey = __('messages.user.account_not_active');
        $e->errors = [$mainMessage];
        return $e;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_PENDING;
    }
}
