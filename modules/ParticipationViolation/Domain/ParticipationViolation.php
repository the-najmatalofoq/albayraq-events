<?php
// modules/ParticipationViolation/Domain/ParticipationViolation.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\IAM\Domain\ValueObject\UserId;

final class ParticipationViolation extends AggregateRoot
{
    public function __construct(
        public readonly ViolationId $uuid,
        public readonly ParticipationId $participationId,
        public readonly ViolationTypeId $violationTypeId,
        public readonly TranslatableText $description,
        public readonly UserId $issuedBy,
        public readonly \DateTimeImmutable $occurredAt,
        public readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {}

    public static function create(
        ViolationId $uuid,
        ParticipationId $participationId,
        ViolationTypeId $violationTypeId,
        TranslatableText $description,
        UserId $issuedBy,
        \DateTimeImmutable $occurredAt
    ): self {
        return new self($uuid, $participationId, $violationTypeId, $description, $issuedBy, $occurredAt);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
