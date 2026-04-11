<?php
// modules/EventRoleAssignment/Application/Handlers/Crm/CrmUpdateEventRoleAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Application\Handlers\Crm;

use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Domain\EventRoleAssignment;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleAssignment\Application\Commands\Crm\CrmUpdateEventRoleAssignmentCommand;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Role\Domain\ValueObject\RoleId;

final readonly class CrmUpdateEventRoleAssignmentHandler
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmUpdateEventRoleAssignmentCommand $command): void
    {
        $assignment = $this->repository->findById(AssignmentId::fromString($command->id));

        if ($assignment === null) {
            throw new \DomainException("Assignment {$command->id} not found.");
        }

        // EventRoleAssignment is immutable in this case, but we can replace it or update if domain allowed.
        // Usually, an update on an assignment means changing the role.
        
        $newAssignment = new EventRoleAssignment(
            uuid: $assignment->uuid,
            eventId: EventId::fromString($command->eventId),
            userId: UserId::fromString($command->userId),
            roleId: RoleId::fromString($command->roleId),
        );

        $this->repository->save($newAssignment);
    }
}
