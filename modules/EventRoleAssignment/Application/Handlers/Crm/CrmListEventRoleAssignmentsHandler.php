<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmListEventRoleAssignmentsHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Crm;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Application\Queries\Crm\CrmListEventRoleAssignmentsQuery;
use Modules\Event\Domain\ValueObject\EventId;

final readonly class CrmListEventRoleAssignmentsHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmListEventRoleAssignmentsQuery $query): array
    {
        if ($query->eventId) {
            return $this->repository->findByEventId(EventId::fromString($query->eventId));
        }
        
        return [];
    }
}
