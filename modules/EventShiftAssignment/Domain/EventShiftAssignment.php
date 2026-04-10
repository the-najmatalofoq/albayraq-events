<?php
// modules/EventShiftAssignment/Domain/EventShiftAssignment.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;
use Modules\EventShiftAssignment\Domain\Enum\ShiftAssignmentStatusEnum;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventShift\Domain\ValueObject\ShiftId;

final class EventShiftAssignment extends AggregateRoot
{
    private function __construct(
        public readonly ShiftAssignmentId $uuid,
        public readonly ParticipationId $participationId,
        public readonly ShiftId $shiftId,
        public private(set) ShiftAssignmentStatusEnum $status,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public static function create(
        ShiftAssignmentId $uuid,
        ParticipationId $participationId,
        ShiftId $shiftId,
    ): self {
        return new self(
            uuid: $uuid,
            participationId: $participationId,
            shiftId: $shiftId,
            status: ShiftAssignmentStatusEnum::ACTIVE,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        ShiftAssignmentId $uuid,
        ParticipationId $participationId,
        ShiftId $shiftId,
        ShiftAssignmentStatusEnum $status,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
    ): self {
        return new self($uuid, $participationId, $shiftId, $status, $createdAt, $updatedAt);
    }

    public function cancel(): void
    {
        if ($this->status === ShiftAssignmentStatusEnum::CANCELLED) {
            return;
        }
        $this->status = ShiftAssignmentStatusEnum::CANCELLED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isActive(): bool
    {
        return $this->status === ShiftAssignmentStatusEnum::ACTIVE;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
