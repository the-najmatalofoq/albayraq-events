<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Event;

use DateTimeImmutable;
use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\DomainEvent;

final readonly class OtpRequested implements DomainEvent
{
    public function __construct(
        public UserId $userId,
        public string $code,
        public OtpPurposeEnum $purpose,
        public DateTimeImmutable $occurredOn = new DateTimeImmutable(),
    ) {
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
