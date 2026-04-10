<?php
// modules/Event/Infrastructure/Persistence/Eloquent/EloquentEventRepository.php
declare(strict_types=1);

namespace Modules\Event\Infrastructure\Persistence\Eloquent;

use Modules\Event\Domain\Event;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\Event\Infrastructure\Persistence\EventReflector;

final class EloquentEventRepository implements EventRepositoryInterface
{
    public function nextIdentity(): EventId
    {
        return EventId::generate();
    }

    public function save(Event $event): void
    {
        EventModel::updateOrCreate(
            ['id' => $event->uuid->value],
            [
                'name' => $event->name->toArray(),
                'description' => $event->description?->toArray(),
                'start_date' => $event->startDate->format('Y-m-d H:i:s'),
                'end_date' => $event->endDate->format('Y-m-d H:i:s'),
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'geofence_radius' => $event->geofenceRadius,
                'address' => $event->address,
                'status' => $event->status->value,
                'created_by' => $event->createdBy->value,
            ]
        );
    }

    public function findById(EventId $id): ?Event
    {
        $model = EventModel::find($id->value);
        return $model ? EventReflector::fromModel($model) : null;
    }

    public function findBySlug(string $slug): ?Event
    {
        $model = EventModel::where('slug', $slug)->first();
        return $model ? EventReflector::fromModel($model) : null;
    }

    public function listAll(): array
    {
        return EventModel::all()
            ->map(fn($model) => EventReflector::fromModel($model))
            ->toArray();
    }

    public function findByIds(array $ids): array
    {
        return EventModel::whereIn('id', $ids)
            ->get()
            ->map(fn(EventModel $m) => EventReflector::fromModel($m))
            ->toArray();
    }

    public function delete(EventId $id): void
    {
        EventModel::where('id', $id->value)->delete();
    }
}
