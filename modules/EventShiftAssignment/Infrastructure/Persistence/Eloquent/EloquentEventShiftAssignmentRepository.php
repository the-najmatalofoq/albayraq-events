<?php
// modules/EventShiftAssignment/Infrastructure/Persistence/Eloquent/EloquentEventShiftAssignmentRepository.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\EventShiftAssignment\Domain\EventShiftAssignment;
use Modules\EventShiftAssignment\Domain\Enum\ShiftAssignmentStatusEnum;
use Modules\EventShiftAssignment\Domain\Repository\EventShiftAssignmentRepositoryInterface;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;

final class EloquentEventShiftAssignmentRepository implements EventShiftAssignmentRepositoryInterface
{
    public function nextIdentity(): ShiftAssignmentId
    {
        return ShiftAssignmentId::generate();
    }

    public function save(EventShiftAssignment $assignment): void
    {
        EventShiftAssignmentModel::updateOrCreate(
            ['id' => $assignment->uuid->value],
            [
                'participation_id' => $assignment->participationId->value,
                'shift_id' => $assignment->shiftId->value,
                'status' => $assignment->status->value,
            ]
        );
    }

    public function findById(ShiftAssignmentId $id): ?EventShiftAssignment
    {
        $m = EventShiftAssignmentModel::find($id->value);
        return $m ? $this->toEntity($m) : null;
    }

    public function findByParticipationId(ParticipationId $id): array
    {
        return EventShiftAssignmentModel::where('participation_id', $id->value)
            ->get()->map(fn(EventShiftAssignmentModel $m) => $this->toEntity($m))->toArray();
    }

    public function findByShiftId(ShiftId $id): array
    {
        return EventShiftAssignmentModel::where('shift_id', $id->value)
            ->get()->map(fn(EventShiftAssignmentModel $m) => $this->toEntity($m))->toArray();
    }

    public function findByParticipationAndShift(ParticipationId $participationId, ShiftId $shiftId): ?EventShiftAssignment
    {
        $m = EventShiftAssignmentModel::where('participation_id', $participationId->value)
            ->where('shift_id', $shiftId->value)
            ->first();
        return $m ? $this->toEntity($m) : null;
    }

    public function countActiveByShiftId(ShiftId $shiftId): int
    {
        return EventShiftAssignmentModel::where('shift_id', $shiftId->value)
            ->where('status', ShiftAssignmentStatusEnum::ACTIVE->value)
            ->count();
    }

    private function toEntity(EventShiftAssignmentModel $m): EventShiftAssignment
    {
        return EventShiftAssignment::reconstitute(
            uuid: ShiftAssignmentId::fromString($m->id),
            participationId: ParticipationId::fromString($m->participation_id),
            shiftId: ShiftId::fromString($m->shift_id),
            status: ShiftAssignmentStatusEnum::from($m->status),
            createdAt: new DateTimeImmutable($m->created_at->toDateTimeString()),
            updatedAt: $m->updated_at ? new DateTimeImmutable($m->updated_at->toDateTimeString()) : null,
        );
    }
}
