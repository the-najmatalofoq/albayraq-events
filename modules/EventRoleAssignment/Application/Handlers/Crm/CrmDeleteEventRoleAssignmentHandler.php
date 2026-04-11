<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmDeleteEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Crm;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Commands\Crm\CrmDeleteEventRoleAssignmentCommand;

final readonly class CrmDeleteEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmDeleteEventRoleAssignmentCommand $command): void
    {
        $this->repository->delete(AssignmentId::fromString($command->id));
    }
}
