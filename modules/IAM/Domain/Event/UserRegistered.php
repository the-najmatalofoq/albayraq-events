<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Event;

use DateTimeImmutable;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\DomainEvent;
use Modules\User\Domain\ValueObject\Phone;

final class UserRegistered implements DomainEvent
{
    public function __construct(
        public readonly UserId $userId,
        public readonly Phone $phone,
        public readonly DateTimeImmutable $occurredOn = new DateTimeImmutable,
    ) {
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
