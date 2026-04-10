<?php
// modules/EventJoinRequest/Domain/EventJoinRequest.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\EventJoinRequest\Domain\Enum\JoinRequestStatusEnum;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final class EventJoinRequest extends AggregateRoot
{
    private function __construct(
        public readonly JoinRequestId $uuid,
        public readonly UserId $userId,
        public readonly EventId $eventId,
        public readonly PositionId $positionId,
        public private(set) JoinRequestStatusEnum $status,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?string $rejectionReason = null,
        public private(set) ?UserId $reviewedBy = null,
        public private(set) ?DateTimeImmutable $reviewedAt = null,
    ) {}

    public static function create(
        JoinRequestId $uuid,
        UserId $userId,
        EventId $eventId,
        PositionId $positionId,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            eventId: $eventId,
            positionId: $positionId,
            status: JoinRequestStatusEnum::PENDING,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        JoinRequestId $uuid,
        UserId $userId,
        EventId $eventId,
        PositionId $positionId,
        JoinRequestStatusEnum $status,
        DateTimeImmutable $createdAt,
        ?string $rejectionReason = null,
        ?UserId $reviewedBy = null,
        ?DateTimeImmutable $reviewedAt = null,
    ): self {
        return new self($uuid, $userId, $eventId, $positionId, $status, $createdAt, $rejectionReason, $reviewedBy, $reviewedAt);
    }

    public function approve(UserId $reviewerId): void
    {
        $this->status = JoinRequestStatusEnum::APPROVED;
        $this->reviewedBy = $reviewerId;
        $this->reviewedAt = new DateTimeImmutable();
    }

    public function reject(UserId $reviewerId, string $reason): void
    {
        $this->status = JoinRequestStatusEnum::REJECTED;
        $this->reviewedBy = $reviewerId;
        $this->reviewedAt = new DateTimeImmutable();
        $this->rejectionReason = $reason;
    }

    public function isPending(): bool
    {
        return $this->status->isPending();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
