<?php
// modules/EventStaffingPosition/Presentation/Http/Presenter/EventStaffingPositionPresenter.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Presenter;

use Modules\EventStaffingPosition\Domain\EventStaffingPosition;

final class EventStaffingPositionPresenter
{
    public static function fromDomain(EventStaffingPosition $position): array
    {
        return [
            'id' => $position->uuid->value,
            'event_id' => $position->eventId->value,
            'title' => $position->title->toArray(),
            'requirements' => $position->requirements->toArray(),
            'quantity' => $position->quantity,
            'is_active' => $position->isActive,
        ];
    }
}
