<?php
// modules/EventPositionApplication/Domain/EventPositionApplication.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationStatus;

final class EventPositionApplication extends AggregateRoot
{
    public function __construct(
        public readonly ApplicationId $uuid,
        public readonly UserId $userId,
        public readonly PositionId $positionId,
        public private(set) ApplicationStatus $status,
        public private(set) float $rankingScore = 0.0,
        public readonly \DateTimeImmutable $appliedAt = new \DateTimeImmutable(),
        public private(set) ?\DateTimeImmutable $reviewedAt = null,
        public private(set) ?UserId $reviewedBy = null
    ) {}

    public static function create(
        ApplicationId $uuid,
        UserId $userId,
        PositionId $positionId,
        ApplicationStatus $status = ApplicationStatus::PENDING,
        float $rankingScore = 0.0
    ): self {
        return new self($uuid, $userId, $positionId, $status, $rankingScore);
    }

    public function approve(UserId $reviewerId): void
    {
        $this->status = ApplicationStatus::APPROVED;
        $this->reviewedAt = new \DateTimeImmutable();
        $this->reviewedBy = $reviewerId;
    }

    public function reject(UserId $reviewerId): void
    {
        $this->status = ApplicationStatus::REJECTED;
        $this->reviewedAt = new \DateTimeImmutable();
        $this->reviewedBy = $reviewerId;
    }

    public function cancel(): void
    {
        $this->status = ApplicationStatus::CANCELLED;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
