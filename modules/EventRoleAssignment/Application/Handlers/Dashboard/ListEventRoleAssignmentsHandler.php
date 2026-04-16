<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmListEventRoleAssignmentsHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Dashboard;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Application\Queries\Dashboard\DashboardListEventRoleAssignmentsQuery;
use Modules\Event\Domain\ValueObject\EventId;

final readonly class ListEventRoleAssignmentsHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(ListEventRoleAssignmentsQuery $query): array
    {
        if ($query->eventId) {
            return $this->repository->findByEventId(EventId::fromString($query->eventId));
        }
        
        return [];
    }
}
