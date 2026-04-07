<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class InvalidCredentialsException extends DomainException
{
    public function __construct(array $errors = [])
    {
        $mainMessage = __('messages.errors.invalid_credentials');
        parent::__construct($mainMessage);
        $this->errors = empty($errors) ? ['credentials' => [$mainMessage]] : $errors;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::INVALID_CREDENTIALS;
    }
}
