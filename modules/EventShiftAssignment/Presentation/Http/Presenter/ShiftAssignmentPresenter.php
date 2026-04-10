<?php
// modules/EventShiftAssignment/Presentation/Http/Presenter/ShiftAssignmentPresenter.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Presentation\Http\Presenter;

use Modules\EventShiftAssignment\Domain\EventShiftAssignment;

final class ShiftAssignmentPresenter
{
    public static function fromDomain(EventShiftAssignment $a): array
    {
        return [
            'id' => $a->uuid->value,
            'participation_id' => $a->participationId->value,
            'shift_id' => $a->shiftId->value,
            'status' => $a->status->value,
            'created_at' => $a->createdAt->format(DATE_ATOM),
        ];
    }
}
