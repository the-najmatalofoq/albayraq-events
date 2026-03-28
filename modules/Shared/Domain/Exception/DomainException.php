<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;

abstract class DomainException extends \Exception
{
    abstract public function getErrorCode(): ErrorCodeEnum;

    public function getStatusCode(): int
    {
        return $this->getErrorCode()->getHttpStatus();
    }
}

