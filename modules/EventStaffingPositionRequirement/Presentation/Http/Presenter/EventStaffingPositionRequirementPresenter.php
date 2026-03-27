<?php
// modules/EventStaffingPositionRequirement/Presentation/Http/Presenter/EventStaffingPositionRequirementPresenter.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Presentation\Http\Presenter;

use Modules\EventStaffingPositionRequirement\Domain\EventStaffingPositionRequirement;

final class EventStaffingPositionRequirementPresenter
{
    public static function fromDomain(EventStaffingPositionRequirement $requirement): array
    {
        return [
            'id' => $requirement->uuid->value,
            'position_id' => $requirement->positionId->value,
            'title' => $requirement->title->toArray(),
            'is_required' => $requirement->isRequired,
            'description' => $requirement->description,
        ];
    }
}
