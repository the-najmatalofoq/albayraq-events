<?php
// modules/Event/Domain/Exception/EventNotFoundException.php
declare(strict_types=1);

namespace Modules\Event\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\Event\Domain\ValueObject\EventId;

final class EventNotFoundException extends DomainException
{
    public static function withId(EventId $id): self
    {
        return new self("Event with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
