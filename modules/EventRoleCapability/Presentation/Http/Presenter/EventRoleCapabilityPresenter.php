<?php
// modules/EventRoleCapability/Presentation/Http/Presenter/EventRoleCapabilityPresenter.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Presenter;

use Modules\EventRoleCapability\Domain\EventRoleCapability;

final class EventRoleCapabilityPresenter
{
    public static function fromDomain(EventRoleCapability $capability): array
    {
        return [
            'id' => $capability->uuid->value,
            'assignment_id' => $capability->assignmentId->value,
            'capability_key' => $capability->capabilityKey,
            'is_granted' => $capability->isGranted,
        ];
    }
}
