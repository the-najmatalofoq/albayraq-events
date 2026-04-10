<?php
// modules/EventShiftAssignment/Domain/Exception/ShiftFullException.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftFullException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Shift full: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
