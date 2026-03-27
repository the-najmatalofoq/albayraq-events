<?php
// modules/EventParticipation/Domain/EventParticipation.php
declare(strict_types=1);

namespace Modules\EventParticipation\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationStatus;

final class EventParticipation extends AggregateRoot
{
    public function __construct(
        public readonly ParticipationId $uuid,
        public readonly UserId $userId,
        public readonly EventId $eventId,
        public readonly PositionId $positionId,
        public private(set) ?GroupId $groupId = null,
        public private(set) ?string $employeeNumber = null,
        public private(set) ParticipationStatus $status = ParticipationStatus::ACTIVE,
        public readonly \DateTimeImmutable $startedAt = new \DateTimeImmutable(),
        public private(set) ?\DateTimeImmutable $endedAt = null
    ) {}

    public static function create(
        ParticipationId $uuid,
        UserId $userId,
        EventId $eventId,
        PositionId $positionId,
        ?GroupId $groupId = null,
        ?string $employeeNumber = null,
        ParticipationStatus $status = ParticipationStatus::ACTIVE
    ): self {
        return new self($uuid, $userId, $eventId, $positionId, $groupId, $employeeNumber, $status);
    }

    public function assignToGroup(GroupId $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function setEmployeeNumber(string $employeeNumber): void
    {
        $this->employeeNumber = $employeeNumber;
    }

    public function complete(): void
    {
        $this->status = ParticipationStatus::COMPLETED;
        $this->endedAt = new \DateTimeImmutable();
    }

    public function cancel(): void
    {
        $this->status = ParticipationStatus::CANCELLED;
        $this->endedAt = new \DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
