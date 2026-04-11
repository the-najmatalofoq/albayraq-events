<?php
// modules/EventRoleAssignment/Application/Commands/Crm/CrmDeleteEventRoleAssignmentCommand.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Commands\Crm;

final readonly class CrmDeleteEventRoleAssignmentCommand
{
    public function __construct(
        public string $id,
    ) {
    }
}
