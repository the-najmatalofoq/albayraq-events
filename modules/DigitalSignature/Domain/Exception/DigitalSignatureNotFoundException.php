<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class DigitalSignatureNotFoundException extends DomainException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Digital signature with id "%s" not found', $id));
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
