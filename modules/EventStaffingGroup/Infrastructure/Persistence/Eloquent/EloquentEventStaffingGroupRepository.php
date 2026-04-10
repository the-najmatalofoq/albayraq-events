<?php
// modules/EventStaffingGroup/Infrastructure/Persistence/Eloquent/EloquentEventStaffingGroupRepository.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent;

use Modules\EventStaffingGroup\Domain\EventStaffingGroup;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Infrastructure\Persistence\EventStaffingGroupReflector;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\UserId;

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
                'leader_id' => $group->leaderId?->value,
                'color' => $group->color->value,
                'is_locked' => $group->isLocked,
            ]
        );
    }

    public function findById(GroupId $id): ?EventStaffingGroup
    {
        $model = EventStaffingGroupModel::find($id->value);
        return $model instanceof EventStaffingGroupModel ? $this->toEntity($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventStaffingGroupModel::where('event_id', $eventId->value)
            ->get()
            ->map(fn(EventStaffingGroupModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function delete(GroupId $id): void
    {
        EventStaffingGroupModel::where('id', $id->value)->delete();
    }

    private function toEntity(EventStaffingGroupModel $m): EventStaffingGroup
    {
        return EventStaffingGroup::reconstitute(
            uuid: GroupId::fromString($m->id),
            eventId: EventId::fromString($m->event_id),
            name: TranslatableText::fromArray($m->name),
            color: HexColor::fromString((string) $m->color),
            isActive: true,
            isLocked: (bool) $m->is_locked,
            leaderId: $m->leader_id ? UserId::fromString($m->leader_id) : null,
        );
    }
}
