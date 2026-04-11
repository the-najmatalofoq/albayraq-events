<?php
// modules/EventRoleAssignment/Application/Commands/Crm/CrmCreateEventRoleAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Commands\Crm;

final readonly class CrmCreateEventRoleAssignmentCommand
{
    public function __construct(
        public string $eventId,
        public string $userId,
        public string $roleId,
    ) {
    }
}
