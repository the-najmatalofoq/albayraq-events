<?php
// modules/IAM/Domain/Exception/CredentialsInvalidException.php
declare(strict_types=1);

namespace Modules\IAM\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class CredentialsInvalidException extends DomainException
{
    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::INVALID_CREDENTIALS;
    }
}
