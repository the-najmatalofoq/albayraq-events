<?php
// modules/EventRoleAssignment/Application/Commands/Crm/CrmUpdateEventRoleAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Commands\Dashboard;

final readonly class UpdateEventRoleAssignmentCommand
{
    public function __construct(
        public string $id,
        public string $eventId,
        public string $userId,
        public string $roleId,
    ) {
    }
}
