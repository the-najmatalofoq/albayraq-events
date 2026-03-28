<?php
// modules/EventPositionApplication/Domain/EventPositionApplication.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationStatusEnum;

final class EventPositionApplication extends AggregateRoot
{
    public function __construct(
        public readonly ApplicationId $uuid,
        public readonly UserId $userId,
        public readonly PositionId $positionId,
        public private(set) ApplicationStatusEnum $status,
        public private(set) float $rankingScore = 0.0,
        public readonly \DateTimeImmutable $appliedAt = new \DateTimeImmutable(),
        public private(set) ?\DateTimeImmutable $reviewedAt = null,
        public private(set) ?UserId $reviewedBy = null
    ) {
    }

    public static function create(
        ApplicationId $uuid,
        UserId $userId,
        PositionId $positionId,
        ApplicationStatusEnum $status = ApplicationStatusEnum::PENDING,
        float $rankingScore = 0.0
    ): self {
        return new self($uuid, $userId, $positionId, $status, $rankingScore);
    }

    public function approve(UserId $reviewerId): void
    {
        $this->status = ApplicationStatusEnum::APPROVED;
        $this->reviewedAt = new \DateTimeImmutable();
        $this->reviewedBy = $reviewerId;
    }

    public function reject(UserId $reviewerId): void
    {
        $this->status = ApplicationStatusEnum::REJECTED;
        $this->reviewedAt = new \DateTimeImmutable();
        $this->reviewedBy = $reviewerId;
    }

    public function cancel(): void
    {
        $this->status = ApplicationStatusEnum::CANCELLED;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
