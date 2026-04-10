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
use Modules\User\Domain\ValueObject\UserId;

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
                'shift_id' => $assignment->shiftId->value,
                'participation_id' => $assignment->participationId->value,
                'status' => $assignment->status->value,
                'assigned_by' => $assignment->assignedBy->value,
                'notes' => $assignment->notes,
            ]
        );
    }

    public function findById(ShiftAssignmentId $id): ?EventShiftAssignment
    {
        $model = EventShiftAssignmentModel::find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByShiftId(ShiftId $shiftId): array
    {
        return EventShiftAssignmentModel::where('shift_id', $shiftId->value)
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByParticipationId(ParticipationId $participationId): array
    {
        return EventShiftAssignmentModel::where('participation_id', $participationId->value)
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    public function countActiveByShiftId(ShiftId $shiftId): int
    {
        return EventShiftAssignmentModel::where('shift_id', $shiftId->value)
            ->where('status', ShiftAssignmentStatusEnum::ASSIGNED->value)
            ->count();
    }

    private function toEntity(EventShiftAssignmentModel $m): EventShiftAssignment
    {
        return EventShiftAssignment::reconstitute(
            uuid: ShiftAssignmentId::fromString($m->id),
            shiftId: ShiftId::fromString($m->shift_id),
            participationId: ParticipationId::fromString($m->participation_id),
            status: ShiftAssignmentStatusEnum::from($m->status),
            assignedBy: UserId::fromString($m->assigned_by),
            notes: $m->notes,
            createdAt: new DateTimeImmutable($m->created_at->toDateTimeString()),
            updatedAt: $m->updated_at ? new DateTimeImmutable($m->updated_at->toDateTimeString()) : null,
        );
    }
}
