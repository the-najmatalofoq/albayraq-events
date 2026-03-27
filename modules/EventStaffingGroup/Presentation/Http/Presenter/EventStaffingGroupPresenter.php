<?php
// modules/EventStaffingGroup/Presentation/Http/Presenter/EventStaffingGroupPresenter.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Presenter;

use Modules\EventStaffingGroup\Domain\EventStaffingGroup;

final class EventStaffingGroupPresenter
{
    public static function fromDomain(EventStaffingGroup $group): array
    {
        return [
            'id' => $group->uuid->value,
            'event_id' => $group->eventId->value,
            'name' => $group->name->toArray(),
            'leader_id' => $group->leaderId->value,
            'color' => $group->color->value,
            'is_active' => $group->isActive,
        ];
    }
}
