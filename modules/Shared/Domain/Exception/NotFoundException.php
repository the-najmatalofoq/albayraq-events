<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;

class NotFoundException extends DomainException
{
    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
