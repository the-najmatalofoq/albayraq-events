<?php
// modules/EventShift/Domain/Exception/ShiftNotFoundException.php
declare(strict_types=1);

namespace Modules\EventShift\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class ShiftNotFoundException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Shift: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
