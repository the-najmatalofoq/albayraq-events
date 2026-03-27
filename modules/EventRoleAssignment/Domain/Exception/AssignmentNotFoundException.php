<?php
// modules/EventRoleAssignment/Domain/Exception/AssignmentNotFoundException.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;

final class AssignmentNotFoundException extends DomainException
{
    public static function withId(AssignmentId $id): self
    {
        return new self("Role assignment with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
