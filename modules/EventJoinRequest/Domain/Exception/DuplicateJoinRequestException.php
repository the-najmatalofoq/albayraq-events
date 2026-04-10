<?php
// modules/EventJoinRequest/Domain/Exception/DuplicateJoinRequestException.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class DuplicateJoinRequestException extends DomainException
{
    public static function create(string $userId, string $eventId): self
    {
        return new self("User {$userId} already has a pending or active request for event {$eventId}");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
