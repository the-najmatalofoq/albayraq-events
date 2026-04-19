<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;

abstract class DomainException extends \Exception
{
    protected string $messageKey = '';
    protected array $messageParams = [];
    protected array $errors = [];

    abstract public function getErrorCode(): ErrorCodeEnum;

    public function getStatusCode(): int
    {
        return $this->getErrorCode()->getHttpStatus();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getMessageKey(): string
    {
        if ($this->messageKey !== '') {
            return $this->messageKey;
        }

        return 'messages.errors.' . strtolower($this->getErrorCode()->value);
    }

    public function getMessageParams(): array
    {
        return $this->messageParams;
    }
}
