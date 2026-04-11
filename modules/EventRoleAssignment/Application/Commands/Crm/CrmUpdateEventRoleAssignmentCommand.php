<?php
// modules/EventRoleAssignment/Application/Commands/Crm/CrmUpdateEventRoleAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Commands\Crm;

final readonly class CrmUpdateEventRoleAssignmentCommand
{
    public function __construct(
        public string $id,
        public string $eventId,
        public string $userId,
        public string $roleId,
    ) {
    }
}
