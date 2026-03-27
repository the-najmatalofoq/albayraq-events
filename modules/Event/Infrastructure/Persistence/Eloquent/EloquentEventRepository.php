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
                'slug' => $event->slug,
                'description' => $event->description->toArray(),
                'type' => $event->type,
                'start_date' => $event->startDate->format('Y-m-d H:i:s'),
                'end_date' => $event->endDate->format('Y-m-d H:i:s'),
                'latitude' => $event->location->latitude,
                'longitude' => $event->location->longitude,
                'price_amount' => $event->price->amount,
                'price_currency' => $event->price->currency,
                'status' => $event->status->value,
                'banner_id' => $event->bannerId,
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
}
