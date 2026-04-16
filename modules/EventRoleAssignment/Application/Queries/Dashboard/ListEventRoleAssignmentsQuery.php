<?php
// modules/EventRoleAssignment/Application/Queries/Crm/CrmListEventRoleAssignmentsQuery.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Queries\Dashboard;

final readonly class ListEventRoleAssignmentsQuery
{
    public function __construct(
        public ?string $eventId = null,
    ) {
    }
}
