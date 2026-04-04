<?php
// modules/EventContract/Domain/EventContract.php
declare(strict_types=1);

namespace Modules\EventContract\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\EventContract\Domain\ValueObject\ContractId;
use Modules\EventContract\Domain\Enum\ContractStatusEnum;

final class EventContract extends AggregateRoot
{
    private function __construct(
        public readonly ContractId $uuid,
        public readonly ParticipationId $participationId,
        public private(set) string $contractType,
        public private(set) float $wageAmount,
        public private(set) array $terms,
        public private(set) ContractStatusEnum $status,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?ContractRejectionReasonId $rejectionReasonId = null,
        public private(set) ?string $rejectionNotes = null,
        public private(set) ?DateTimeImmutable $sentAt = null,
        public private(set) ?DateTimeImmutable $acceptedAt = null,
        public private(set) ?DateTimeImmutable $rejectedAt = null,
    ) {}

    public static function create(
        ContractId $uuid,
        ParticipationId $participationId,
        string $contractType,
        float $wageAmount,
        array $terms
    ): self {
        return new self(
            uuid: $uuid,
            participationId: $participationId,
            contractType: $contractType,
            wageAmount: $wageAmount,
            terms: $terms,
            status: ContractStatusEnum::DRAFT,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        ContractId $uuid,
        ParticipationId $participationId,
        string $contractType,
        float $wageAmount,
        array $terms,
        ContractStatusEnum $status,
        DateTimeImmutable $createdAt,
        ?ContractRejectionReasonId $rejectionReasonId = null,
        ?string $rejectionNotes = null,
        ?DateTimeImmutable $sentAt = null,
        ?DateTimeImmutable $acceptedAt = null,
        ?DateTimeImmutable $rejectedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            participationId: $participationId,
            contractType: $contractType,
            wageAmount: $wageAmount,
            terms: $terms,
            status: $status,
            createdAt: $createdAt,
            rejectionReasonId: $rejectionReasonId,
            rejectionNotes: $rejectionNotes,
            sentAt: $sentAt,
            acceptedAt: $acceptedAt,
            rejectedAt: $rejectedAt,
        );
    }

    public function send(): void
    {
        $this->status = ContractStatusEnum::SENT;
        $this->sentAt = new DateTimeImmutable();
    }

    public function accept(): void
    {
        $this->status = ContractStatusEnum::ACCEPTED;
        $this->acceptedAt = new DateTimeImmutable();
    }

    public function reject(ContractRejectionReasonId $reasonId, ?string $notes = null): void
    {
        $this->status = ContractStatusEnum::REJECTED;
        $this->rejectionReasonId = $reasonId;
        $this->rejectionNotes = $notes;
        $this->rejectedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
