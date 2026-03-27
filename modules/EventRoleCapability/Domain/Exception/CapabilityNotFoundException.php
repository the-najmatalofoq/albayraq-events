<?php
// modules/EventRoleCapability/Domain/Exception/CapabilityNotFoundException.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;

final class CapabilityNotFoundException extends DomainException
{
    public static function withId(CapabilityId $id): self
    {
        return new self("Capability with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
