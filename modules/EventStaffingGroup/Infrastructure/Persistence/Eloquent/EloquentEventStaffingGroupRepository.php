<?php
// modules/EventStaffingGroup/Infrastructure/Persistence/Eloquent/EloquentEventStaffingGroupRepository.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent;

use Modules\EventStaffingGroup\Domain\EventStaffingGroup;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Infrastructure\Persistence\EventStaffingGroupReflector;

final class EloquentEventStaffingGroupRepository implements EventStaffingGroupRepositoryInterface
{
    public function nextIdentity(): GroupId
    {
        return GroupId::generate();
    }

    public function save(EventStaffingGroup $group): void
    {
        EventStaffingGroupModel::updateOrCreate(
            ['id' => $group->uuid->value],
            [
                'event_id' => $group->eventId->value,
                'name' => $group->name->toArray(),
                'leader_id' => $group->leaderId->value,
                'color' => $group->color->value,
                'is_active' => $group->isActive,
            ]
        );
    }

    public function findById(GroupId $id): ?EventStaffingGroup
    {
        $model = EventStaffingGroupModel::find($id->value);
        return $model ? EventStaffingGroupReflector::fromModel($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventStaffingGroupModel::where('event_id', $eventId->value)
            ->get()
            ->map(function (EventStaffingGroupModel $model) {
                return EventStaffingGroupReflector::fromModel($model);
            })
            ->toArray();
    }
}
