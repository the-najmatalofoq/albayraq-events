<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Domain\Event;

use DateTimeImmutable;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\Shared\Domain\DomainEvent;

final class DigitalSignatureCreated implements DomainEvent
{
    public function __construct(
        public DigitalSignatureId $signatureId,
        public string $contractId,
        public DateTimeImmutable $signedAt,
    ) {}

    public function occurredAt(): DateTimeImmutable
    {
        return $this->signedAt;
    }

    public function eventName(): string
    {
        return 'digital_signature.created';
    }
}
