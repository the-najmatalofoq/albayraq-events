<?php
// modules/EventShiftAssignment/Domain/Exception/ShiftCapacityExceededException.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftCapacityExceededException extends DomainException
{
    public static function create(string $shiftId): self
    {
        return new self("Shift {$shiftId} has reached its maximum assignee capacity.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::SHIFT_CAPACITY_EXCEEDED;
    }
}
