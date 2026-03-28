<?php
// modules/ParticipationViolation/Domain/ParticipationViolation.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ParticipationViolation\Domain\Enum\ViolationStatusEnum;
use Modules\User\Domain\ValueObject\UserId;

final class ParticipationViolation extends AggregateRoot
{
    private function __construct(
        public readonly ViolationId $uuid,
        public readonly ParticipationId $participationId,
        public readonly ViolationTypeId $violationTypeId,
        public readonly UserId $reportedBy,
        public private(set) ?string $description,
        public private(set) DateTimeImmutable $date,
        public private(set) int $currentTier,
        public private(set) ViolationStatusEnum $status,
        public private(set) ?float $deductionAmount,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?UserId $approvedBy = null,
        public private(set) ?DateTimeImmutable $approvedAt = null,
    ) {}

    public static function create(
        ViolationId $uuid,
        ParticipationId $participationId,
        ViolationTypeId $violationTypeId,
        UserId $reportedBy,
        DateTimeImmutable $date,
        ?string $description = null,
        int $currentTier = 1
    ): self {
        return new self(
            uuid: $uuid,
            participationId: $participationId,
            violationTypeId: $violationTypeId,
            reportedBy: $reportedBy,
            description: $description,
            date: $date,
            currentTier: $currentTier,
            status: ViolationStatusEnum::PENDING,
            deduction_amount: null,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function approve(UserId $approverId, ?float $deductionAmount = null): void
    {
        $this->status = ViolationStatusEnum::APPROVED;
        $this->approvedBy = $approverId;
        $this->approvedAt = new DateTimeImmutable();
        $this->deductionAmount = $deductionAmount;
    }

    public function escalate(): void
    {
        $this->status = ViolationStatusEnum::ESCALATED;
    }

    public function reject(): void
    {
        $this->status = ViolationStatusEnum::REJECTED;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
