<?php
declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Domain\Exception\DomainException;

final class InvalidPasswordException extends DomainException
{
    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::UNAUTHORIZED;
    }
}
