<?php
// modules/EventStaffingGroup/Domain/Exception/GroupNotFoundException.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;

final class GroupNotFoundException extends DomainException
{
    public static function withId(GroupId $id): self
    {
        return new self("Staffing group with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
