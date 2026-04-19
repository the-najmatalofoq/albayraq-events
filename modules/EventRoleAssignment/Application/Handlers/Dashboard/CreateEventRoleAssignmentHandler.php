<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmCreateEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Dashboard;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\EventRoleAssignment;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Commands\Dashboard\DashboardCreateEventRoleAssignmentCommand;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Role\Domain\ValueObject\RoleId;

final readonly class CreateEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CreateEventRoleAssignmentCommand $command): AssignmentId
    {
        $id = $this->repository->nextIdentity();
        
        $assignment = EventRoleAssignment::create(
            uuid: $id,
            eventId: EventId::fromString($command->eventId),
            userId: UserId::fromString($command->userId),
            roleId: RoleId::fromString($command->roleId),
        );

        $this->repository->save($assignment);

        return $id;
    }
}
