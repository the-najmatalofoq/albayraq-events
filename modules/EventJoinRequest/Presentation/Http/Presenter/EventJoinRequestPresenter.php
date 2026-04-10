<?php
// modules/EventJoinRequest/Presentation/Http/Presenter/EventJoinRequestPresenter.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Presenter;

use Modules\EventJoinRequest\Domain\EventJoinRequest;

final class EventJoinRequestPresenter
{
    public static function fromDomain(EventJoinRequest $r): array
    {
        return [
            'id' => $r->uuid->value,
            'user_id' => $r->userId->value,
            'event_id' => $r->eventId->value,
            'position_id' => $r->positionId->value,
            'status' => $r->status->value,
            'rejection_reason' => $r->rejectionReason,
            'reviewed_by' => $r->reviewedBy?->value,
            'reviewed_at' => $r->reviewedAt?->format(DATE_ATOM),
            'created_at' => $r->createdAt->format(DATE_ATOM),
        ];
    }
}
