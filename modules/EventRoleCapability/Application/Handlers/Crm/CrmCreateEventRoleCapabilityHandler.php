<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmCreateEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmCreateEventRoleCapabilityCommand;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;

final readonly class CrmCreateEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CrmCreateEventRoleCapabilityCommand $command): CapabilityId
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
