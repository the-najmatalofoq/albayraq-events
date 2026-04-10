<?php
// modules/EventShift/Presentation/Http/Presenter/EventShiftPresenter.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Presenter;

use Modules\EventShift\Domain\EventShift;

final class EventShiftPresenter
{
    public static function fromDomain(EventShift $s): array
    {
        return [
            'id' => $s->uuid->value,
            'event_id' => $s->eventId->value,
            'position_id' => $s->positionId->value,
            'label' => $s->label,
            'start_at' => $s->startAt->format(DATE_ATOM),
            'end_at' => $s->endAt->format(DATE_ATOM),
            'max_assignees' => $s->maxAssignees,
            'status' => $s->status->value,
            'created_at' => $s->createdAt->format(DATE_ATOM),
        ];
    }
}
