<?php
// modules/IAM/Domain/Exception/UserNotActiveException.php
declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;
// fix: all Exception must extends DomainException and be like the others and work correct with bootstrap/app.php

final class UserNotActiveException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'User account is not active.',
            errorCode: ErrorCodeEnum::USER_NOT_ACTIVE,
            statusCode: 403
        );
    }
}
