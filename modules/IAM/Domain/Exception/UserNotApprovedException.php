<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class UserNotApprovedException extends DomainException
{
    public static function forUser(): self
    {
        $mainMessage = __('messages.errors.user_not_approved');
        $e = new self($mainMessage);
        $e->messageKey = __('message.user.account_not_active');
        $e->errors = [$mainMessage];
        return $e;
    }
    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::USER_NOT_APPROVED;
    }
}
