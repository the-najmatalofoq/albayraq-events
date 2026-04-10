<?php
// modules/EventShift/Infrastructure/Persistence/Eloquent/EloquentEventShiftRepository.php
declare(strict_types=1);

namespace Modules\EventShift\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\EventShift\Domain\EventShift;
use Modules\EventShift\Domain\Enum\ShiftStatusEnum;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\User\Domain\ValueObject\UserId;

final class EloquentEventShiftRepository implements EventShiftRepositoryInterface
{
    public function nextIdentity(): ShiftId
    {
        return ShiftId::generate();
    }

    public function save(EventShift $shift): void
    {
        EventShiftModel::updateOrCreate(
            ['id' => $shift->uuid->value],
            [
                'event_id' => $shift->eventId->value,
                'position_id' => $shift->positionId->value,
                'label' => $shift->label,
                'start_at' => $shift->startAt->format('Y-m-d H:i:s'),
                'end_at' => $shift->endAt->format('Y-m-d H:i:s'),
                'max_assignees' => $shift->maxAssignees,
                'status' => $shift->status->value,
            ]
        );
    }

    public function findById(ShiftId $id): ?EventShift
    {
        $model = EventShiftModel::find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventShiftModel::where('event_id', $eventId->value)
            ->orderBy('start_at')
            ->get()
            ->map(fn(EventShiftModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByEventAndPosition(EventId $eventId, PositionId $positionId): array
    {
        return EventShiftModel::where('event_id', $eventId->value)
            ->where('position_id', $positionId->value)
            ->orderBy('start_at')
            ->get()
            ->map(fn(EventShiftModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function findOverlapping(EventId $eventId, PositionId $positionId, DateTimeImmutable $startAt, DateTimeImmutable $endAt, ?ShiftId $excludeId = null): array
    {
        $query = EventShiftModel::where('event_id', $eventId->value)
            ->where('position_id', $positionId->value)
            ->where('status', 'active')
            ->where('start_at', '<', $endAt->format('Y-m-d H:i:s'))
            ->where('end_at', '>', $startAt->format('Y-m-d H:i:s'));

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId->value);
        }

        return $query->get()->map(fn(EventShiftModel $m) => $this->toEntity($m))->toArray();
    }

    public function findActiveByUserId(UserId $userId): array
    {
        return EventShiftModel::query()
            ->join('event_shift_assignments', 'event_shifts.id', '=', 'event_shift_assignments.shift_id')
            ->join('event_participations', 'event_shift_assignments.participation_id', '=', 'event_participations.id')
            ->where('event_participations.user_id', $userId->value)
            ->where('event_participations.status', 'active')
            ->where('event_shift_assignments.status', 'active')
            ->select('event_shifts.*')
            ->get()
            ->map(fn(EventShiftModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function delete(ShiftId $id): void
    {
        EventShiftModel::where('id', $id->value)->delete();
    }

    private function toEntity(EventShiftModel $m): EventShift
    {
        return EventShift::reconstitute(
            uuid: ShiftId::fromString($m->id),
            eventId: EventId::fromString($m->event_id),
            positionId: PositionId::fromString($m->position_id),
            label: $m->label,
            startAt: new DateTimeImmutable($m->start_at->toDateTimeString()),
            endAt: new DateTimeImmutable($m->end_at->toDateTimeString()),
            maxAssignees: $m->max_assignees,
            status: ShiftStatusEnum::from($m->status),
            createdAt: new DateTimeImmutable($m->created_at->toDateTimeString()),
            updatedAt: $m->updated_at ? new DateTimeImmutable($m->updated_at->toDateTimeString()) : null,
        );
    }
}
