<?php
// modules/EventRoleAssignment/Application/Commands/Crm/CrmCreateEventRoleAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Commands\Dashboard;

final readonly class CreateEventRoleAssignmentCommand
{
    public function __construct(
        public string $eventId,
        public string $userId,
        public string $roleId,
    ) {
    }
}
