<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmCreateEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Dashboard;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardCreateEventRoleCapabilityCommand;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;

final readonly class CreateEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CreateEventRoleCapabilityCommand $command): CapabilityId
    {
        $id = $this->repository->nextIdentity();
        
        $capability = EventRoleCapability::create(
            uuid: $id,
            assignmentId: AssignmentId::fromString($command->assignmentId),
            capabilityKey: $command->capabilityKey,
            isGranted: $command->isGranted
        );

        $this->repository->save($capability);

        return $id;
    }
}
