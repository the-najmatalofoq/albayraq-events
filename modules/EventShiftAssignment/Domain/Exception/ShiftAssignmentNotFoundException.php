<?php
// modules/EventShiftAssignment/Domain/Exception/ShiftAssignmentNotFoundException.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftAssignmentNotFoundException extends DomainException
{
    public static function create(string $id): self
    {
        return new self("Shift assignment {$id} not found.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::SHIFT_ASSIGNMENT_NOT_FOUND;
    }
}
