<?php
// modules/EventRoleAssignment/Application/Queries/Crm/CrmListEventRoleAssignmentsQuery.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Queries\Crm;

final readonly class CrmListEventRoleAssignmentsQuery
{
    public function __construct(
        public ?string $eventId = null,
    ) {
    }
}
