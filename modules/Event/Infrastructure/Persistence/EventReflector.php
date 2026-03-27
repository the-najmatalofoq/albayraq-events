<?php
// modules/Event/Infrastructure/Persistence/EventReflector.php
declare(strict_types=1);

namespace Modules\Event\Infrastructure\Persistence;

use Modules\Event\Domain\Event;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\GeoPoint;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Event\Domain\Enum\EventStatusEnum;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;

final class EventReflector
{
    public static function fromModel(EventModel $model): Event
    {
        $reflection = new \ReflectionClass(Event::class);
        $event = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => EventId::fromString($model->id),
            'name' => TranslatableText::fromArray($model->name),
            'slug' => $model->slug,
            'description' => TranslatableText::fromArray($model->description),
            'type' => $model->type,
            'startDate' => $model->start_date,
            'endDate' => $model->end_date,
            'location' => new GeoPoint($model->latitude, $model->longitude),
            'price' => new Money($model->price_amount, $model->price_currency),
            'status' => EventStatusEnum::from($model->status),
            'bannerId' => $model->banner_id,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($event, $value);
        }

        return $event;
    }
}
