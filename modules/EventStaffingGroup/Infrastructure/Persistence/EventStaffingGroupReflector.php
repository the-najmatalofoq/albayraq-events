<?php
// modules/EventStaffingGroup/Infrastructure/Persistence/EventStaffingGroupReflector.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Infrastructure\Persistence;

use Modules\EventStaffingGroup\Domain\EventStaffingGroup;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent\EventStaffingGroupModel;

final class EventStaffingGroupReflector
{
    public static function fromModel(EventStaffingGroupModel $model): EventStaffingGroup
    {
        $reflection = new \ReflectionClass(EventStaffingGroup::class);
        $group = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'      => GroupId::fromString($model->id),
            'eventId'   => EventId::fromString($model->event_id),
            'name'      => TranslatableText::fromArray($model->name),
            'color'     => new HexColor($model->color),
            'isLocked'  => (bool) $model->is_locked,
            'isActive'  => (bool) $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($group, $value);
        }

        return $group;
    }
}
