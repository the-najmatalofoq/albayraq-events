<?php
// modules/EventRoleAssignment/Presentation/Http/Presenter/CrmEventRoleAssignmentPresenter.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Presenter;

use Modules\EventRoleAssignment\Domain\EventRoleAssignment;

final readonly class CrmEventRoleAssignmentPresenter
{
    public function present(EventRoleAssignment $assignment): array
    {
        return [
            'id' => $assignment->uuid->value,
            'event_id' => $assignment->eventId->value,
            'user_id' => $assignment->userId->value,
            'role_id' => $assignment->roleId->value,
        ];
    }

    public function presentCollection(array $assignments): array
    {
        return array_map(fn($a) => $this->present($a), $assignments);
    }
}
