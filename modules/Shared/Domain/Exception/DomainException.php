<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCode;

abstract class DomainException extends \Exception
{
    abstract public function getErrorCode(): ErrorCode;

    public function getStatusCode(): int
    {
        return $this->getErrorCode()->getHttpStatus();
    }
}

