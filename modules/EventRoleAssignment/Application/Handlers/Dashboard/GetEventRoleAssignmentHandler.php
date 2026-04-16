<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmGetEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Dashboard;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Queries\Dashboard\DashboardGetEventRoleAssignmentQuery;
use Modules\EventRoleAssignment\Domain\EventRoleAssignment;

final readonly class GetEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(GetEventRoleAssignmentQuery $query): ?EventRoleAssignment
    {
        return $this->repository->findById(AssignmentId::fromString($query->id));
    }
}
