<?php
// modules/EventShiftAssignment/Domain/Exception/DuplicateShiftAssignmentException.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class DuplicateShiftAssignmentException extends DomainException
{
    public static function create(string $participationId, string $shiftId): self
    {
        return new self("Participation {$participationId} is already assigned to shift {$shiftId}.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::DUPLICATE_SHIFT_ASSIGNMENT;
    }
}
