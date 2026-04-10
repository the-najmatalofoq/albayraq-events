<?php
// modules/EventStaffingPosition/Infrastructure/Persistence/EventStaffingPositionReflector.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Infrastructure\Persistence;

use Modules\EventStaffingPosition\Domain\EventStaffingPosition;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel;

final class EventStaffingPositionReflector
{
    public static function fromModel(EventStaffingPositionModel $model): EventStaffingPosition
    {
        $reflection = new \ReflectionClass(EventStaffingPosition::class);
        $position = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => PositionId::fromString($model->id),
            'eventId' => EventId::fromString($model->event_id),
            'title' => TranslatableText::fromArray($model->title),
            'requirements' => TranslatableText::fromArray($model->requirements),
            'headcount' => (int) $model->headcount,
            'wage' => $model->wage_amount ? new Money((float) $model->wage_amount, $model->wage_type) : null,
            'isActive' => (bool) $model->is_announced,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($position, $value);
        }

        return $position;
    }
}
