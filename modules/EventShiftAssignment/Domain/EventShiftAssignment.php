<?php
// modules/EventShiftAssignment/Domain/EventShiftAssignment.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;
use Modules\EventShiftAssignment\Domain\Enum\ShiftAssignmentStatusEnum;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;

final class EventShiftAssignment extends AggregateRoot
{
    private function __construct(
        public readonly ShiftAssignmentId $uuid,
        public readonly ShiftId $shiftId,
        public readonly ParticipationId $participationId,
        public private(set) ShiftAssignmentStatusEnum $status,
        public readonly UserId $assignedBy,
        public private(set) ?string $notes,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        ShiftAssignmentId $uuid,
        ShiftId $shiftId,
        ParticipationId $participationId,
        UserId $assignedBy,
        ?string $notes = null,
    ): self {
        return new self(
            uuid: $uuid,
            shiftId: $shiftId,
            participationId: $participationId,
            status: ShiftAssignmentStatusEnum::ASSIGNED,
            assignedBy: $assignedBy,
            notes: $notes,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        ShiftAssignmentId $uuid,
        ShiftId $shiftId,
        ParticipationId $participationId,
        ShiftAssignmentStatusEnum $status,
        UserId $assignedBy,
        ?string $notes,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self($uuid, $shiftId, $participationId, $status, $assignedBy, $notes, $createdAt, $updatedAt);
    }

    public function markCompleted(): void
    {
        $this->status = ShiftAssignmentStatusEnum::COMPLETED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function markMissed(): void
    {
        $this->status = ShiftAssignmentStatusEnum::MISSED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function cancel(): void
    {
        $this->status = ShiftAssignmentStatusEnum::CANCELLED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
