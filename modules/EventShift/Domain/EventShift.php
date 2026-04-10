<?php
// modules/EventShift/Domain/EventShift.php
declare(strict_types=1);

namespace Modules\EventShift\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\EventShift\Domain\Enum\ShiftStatusEnum;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final class EventShift extends AggregateRoot
{
    private function __construct(
        public readonly ShiftId $uuid,
        public readonly EventId $eventId,
        public readonly PositionId $positionId,
        public private(set) string $label,
        public private(set) DateTimeImmutable $startAt,
        public private(set) DateTimeImmutable $endAt,
        public private(set) ?int $maxAssignees,
        public private(set) ShiftStatusEnum $status,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        ShiftId $uuid,
        EventId $eventId,
        PositionId $positionId,
        string $label,
        DateTimeImmutable $startAt,
        DateTimeImmutable $endAt,
        ?int $maxAssignees = null,
    ): self {
        if ($endAt <= $startAt) {
            throw new \InvalidArgumentException('Shift end must be after start.');
        }

        return new self(
            uuid: $uuid,
            eventId: $eventId,
            positionId: $positionId,
            label: $label,
            startAt: $startAt,
            endAt: $endAt,
            maxAssignees: $maxAssignees,
            status: ShiftStatusEnum::ACTIVE,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        ShiftId $uuid,
        EventId $eventId,
        PositionId $positionId,
        string $label,
        DateTimeImmutable $startAt,
        DateTimeImmutable $endAt,
        ?int $maxAssignees,
        ShiftStatusEnum $status,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self($uuid, $eventId, $positionId, $label, $startAt, $endAt, $maxAssignees, $status, $createdAt, $updatedAt);
    }

    public function update(string $label, DateTimeImmutable $startAt, DateTimeImmutable $endAt, ?int $maxAssignees): void
    {
        if ($endAt <= $startAt) {
            throw new \InvalidArgumentException('Shift end must be after start.');
        }
        $this->label = $label;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->maxAssignees = $maxAssignees;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function cancel(): void
    {
        $this->status = ShiftStatusEnum::CANCELLED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isActive(): bool
    {
        return $this->status === ShiftStatusEnum::ACTIVE;
    }

    public function overlapsWith(DateTimeImmutable $otherStart, DateTimeImmutable $otherEnd): bool
    {
        return $this->startAt < $otherEnd && $this->endAt > $otherStart;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
