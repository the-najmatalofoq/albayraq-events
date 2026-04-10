<?php
// modules/EventJoinRequest/Domain/Exception/DuplicateJoinRequestException.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class DuplicateJoinRequestException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Duplicate join request: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
