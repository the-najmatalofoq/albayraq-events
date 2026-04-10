<?php
// modules/EventShift/Domain/Exception/ShiftTimeOverlapException.php
declare(strict_types=1);

namespace Modules\EventShift\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftTimeOverlapException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Shift time overlap: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
