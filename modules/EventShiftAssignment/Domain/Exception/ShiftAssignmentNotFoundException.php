<?php
// modules/EventShiftAssignment/Domain/Exception/ShiftAssignmentNotFoundException.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftAssignmentNotFoundException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Assignment: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
