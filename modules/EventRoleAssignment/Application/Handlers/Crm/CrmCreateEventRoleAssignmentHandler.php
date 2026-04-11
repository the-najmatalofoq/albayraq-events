<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmCreateEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Crm;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\EventRoleAssignment;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Commands\Crm\CrmCreateEventRoleAssignmentCommand;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Role\Domain\ValueObject\RoleId;

final readonly class CrmCreateEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmCreateEventRoleAssignmentCommand $command): AssignmentId
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
