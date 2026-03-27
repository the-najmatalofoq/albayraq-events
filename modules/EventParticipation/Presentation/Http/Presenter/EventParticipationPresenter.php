<?php
// modules/EventParticipation/Presentation/Http/Presenter/EventParticipationPresenter.php
declare(strict_types=1);

namespace Modules\EventParticipation\Presentation\Http\Presenter;

use Modules\EventParticipation\Domain\EventParticipation;

final class EventParticipationPresenter
{
    public static function fromDomain(EventParticipation $participation): array
    {
        return [
            'id' => $participation->uuid->value,
            'user_id' => $participation->userId->value,
            'event_id' => $participation->eventId->value,
            'position_id' => $participation->positionId->value,
            'group_id' => $participation->groupId?->value,
            'employee_number' => $participation->employeeNumber,
            'status' => $participation->status->value,
            'status_label' => $participation->status->label(),
            'started_at' => $participation->startedAt->format('Y-m-d H:i:s'),
            'ended_at' => $participation->endedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
