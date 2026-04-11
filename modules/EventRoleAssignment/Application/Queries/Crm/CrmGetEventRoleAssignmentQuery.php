<?php
// modules/EventRoleAssignment/Application/Queries/Crm/CrmGetEventRoleAssignmentQuery.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Queries\Crm;

final readonly class CrmGetEventRoleAssignmentQuery
{
    public function __construct(
        public string $id,
    ) {
    }
}
