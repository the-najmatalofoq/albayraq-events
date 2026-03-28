<?php
// modules/Event/Infrastructure/Persistence/EventReflector.php
declare(strict_types=1);

namespace Modules\Event\Infrastructure\Persistence;

use Modules\Event\Domain\Event;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\Enum\EventStatusEnum;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\User\Domain\ValueObject\UserId;

final class EventReflector
{
    public static function fromModel(EventModel $model): Event
    {
        $reflection = new \ReflectionClass(Event::class);
        $event = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => EventId::fromString($model->id),
            'name'              => TranslatableText::fromArray($model->name),
            'description'       => $model->description ? TranslatableText::fromArray($model->description) : null,
            'latitude'          => (float) $model->latitude,
            'longitude'         => (float) $model->longitude,
            'geofenceRadius'    => (int) $model->geofence_radius,
            'address'           => $model->address,
            'startDate'         => $model->start_date->toDateTimeImmutable(),
            'endDate'           => $model->end_date->toDateTimeImmutable(),
            'dailyStartTime'    => $model->daily_start_time,
            'dailyEndTime'      => $model->daily_end_time,
            'employmentTerms'   => $model->employment_terms ? TranslatableText::fromArray($model->employment_terms) : null,
            'status'            => EventStatusEnum::from($model->status),
            'createdBy'         => UserId::fromString($model->created_by),
            'createdAt'         => $model->created_at->toDateTimeImmutable(),
            'updatedAt'         => $model->updated_at?->toDateTimeImmutable(),
            'deletedAt'         => $model->deleted_at?->toDateTimeImmutable(),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($event, $value);
        }

        return $event;
    }
}
