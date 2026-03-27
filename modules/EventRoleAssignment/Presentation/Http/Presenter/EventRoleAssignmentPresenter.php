<?php
// modules/EventRoleAssignment/Presentation/Http/Presenter/EventRoleAssignmentPresenter.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Presenter;

use Modules\EventRoleAssignment\Domain\EventRoleAssignment;

final class EventRoleAssignmentPresenter
{
    public static function fromDomain(EventRoleAssignment $assignment): array
    {
        return [
            'id' => $assignment->uuid->value,
            'event_id' => $assignment->eventId->value,
            'user_id' => $assignment->userId->value,
            'role_id' => $assignment->roleId->value,
        ];
    }
}
