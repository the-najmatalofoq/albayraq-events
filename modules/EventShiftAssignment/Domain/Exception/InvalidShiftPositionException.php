<?php
// modules/EventShiftAssignment/Domain/Exception/InvalidShiftPositionException.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class InvalidShiftPositionException extends DomainException
{
    public static function create(string $shiftId, string $participationId): self
    {
        return new self("Shift {$shiftId} position does not match participation {$participationId} position.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::INVALID_SHIFT_POSITION;
    }
}
