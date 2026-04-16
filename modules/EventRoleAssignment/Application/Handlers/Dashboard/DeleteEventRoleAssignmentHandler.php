<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmDeleteEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Dashboard;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Commands\Dashboard\DashboardDeleteEventRoleAssignmentCommand;

final readonly class DeleteEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(DeleteEventRoleAssignmentCommand $command): void
    {
        $this->repository->delete(AssignmentId::fromString($command->id));
    }
}
