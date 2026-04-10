<?php
// modules/EventShiftAssignment/Presentation/Http/Presenter/EventShiftAssignmentPresenter.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Presentation\Http\Presenter;

use Modules\EventShiftAssignment\Domain\EventShiftAssignment;

final class EventShiftAssignmentPresenter
{
    public static function fromDomain(EventShiftAssignment $a): array
    {
        return [
            'id' => $a->uuid->value,
            'shift_id' => $a->shiftId->value,
            'participation_id' => $a->participationId->value,
            'status' => $a->status->value,
            'assigned_by' => $a->assignedBy->value,
            'notes' => $a->notes,
            'created_at' => $a->createdAt->format(DATE_ATOM),
        ];
    }
}
