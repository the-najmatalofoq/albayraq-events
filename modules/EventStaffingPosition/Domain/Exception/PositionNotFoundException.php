<?php
// modules/EventStaffingPosition/Domain/Exception/PositionNotFoundException.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final class PositionNotFoundException extends DomainException
{
    public static function withId(PositionId $id): self
    {
        return new self("Staffing position with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
