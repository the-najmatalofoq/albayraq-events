<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Event;

use DateTimeImmutable;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Domain\DomainEvent;

final class UserRegistered implements DomainEvent
{
    public function __construct(
        public readonly UserId $userId,
        public readonly string $phone,
        public readonly DateTimeImmutable $occurredOn = new DateTimeImmutable,
    ) {}

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
