<?php
// modules/EventJoinRequest/Domain/Exception/ShiftConflictException.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftConflictException extends DomainException
{
    public static function create(string $context): self
    {
        $exception = new self("Shift time conflict detected: " . $context);
        return $exception;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::SHIFT_CONFLICT;
    }
}
