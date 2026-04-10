<?php
// modules/EventJoinRequest/Domain/Exception/JoinRequestNotFoundException.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class JoinRequestNotFoundException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Join request: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
