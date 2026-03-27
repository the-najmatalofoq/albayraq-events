<?php
// modules/EventStaffingPosition/Infrastructure/Persistence/Eloquent/EloquentEventStaffingPositionRepository.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent;

use Modules\EventStaffingPosition\Domain\EventStaffingPosition;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\EventStaffingPosition\Infrastructure\Persistence\EventStaffingPositionReflector;

final class EloquentEventStaffingPositionRepository implements EventStaffingPositionRepositoryInterface
{
    public function nextIdentity(): PositionId
    {
        return PositionId::generate();
    }

    public function save(EventStaffingPosition $position): void
    {
        EventStaffingPositionModel::updateOrCreate(
            ['id' => $position->uuid->value],
            [
                'event_id' => $position->eventId->value,
                'title' => $position->title->toArray(),
                'requirements' => $position->requirements->toArray(),
                'quantity' => $position->quantity,
                'is_active' => $position->isActive,
            ]
        );
    }

    public function findById(PositionId $id): ?EventStaffingPosition
    {
        $model = EventStaffingPositionModel::find($id->value);
        return $model ? EventStaffingPositionReflector::fromModel($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventStaffingPositionModel::where('event_id', $eventId->value)
            ->get()
            ->map(function (EventStaffingPositionModel $model) {
                return EventStaffingPositionReflector::fromModel($model);
            })
            ->toArray();
    }
}
