<?php
// modules/ViolationType/Domain/Exception/ViolationTypeNotFoundException.php
declare(strict_types=1);

namespace Modules\ViolationType\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;

final class ViolationTypeNotFoundException extends DomainException
{
    public static function withId(ViolationTypeId $id): self
    {
        return new self("Violation type with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
