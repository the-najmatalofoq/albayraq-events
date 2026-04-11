<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmGetEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Crm;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Queries\Crm\CrmGetEventRoleAssignmentQuery;
use Modules\EventRoleAssignment\Domain\EventRoleAssignment;

final readonly class CrmGetEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmGetEventRoleAssignmentQuery $query): ?EventRoleAssignment
    {
        return $this->repository->findById(AssignmentId::fromString($query->id));
    }
}
