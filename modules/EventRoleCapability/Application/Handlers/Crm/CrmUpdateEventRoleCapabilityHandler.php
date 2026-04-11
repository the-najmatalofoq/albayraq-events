<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmUpdateEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmUpdateEventRoleCapabilityCommand;

final readonly class CrmUpdateEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmUpdateEventRoleCapabilityCommand $command): void
    {
        $capability = $this->repository->findByIdWithTrashed(CapabilityId::fromString($command->id));

        if ($capability === null) {
            // fix: we need in a project wide to make the exceptions only accept translation keys, and we must map each one 
            // fix: like each module have its own translation files (we must load them in the service provider of each moduel)
            // fix: so, we must make it translateable.
            throw new \DomainException("Capability {$command->id} not found.");
        }

        $updatedCapability = new EventRoleCapability(
            uuid: $capability->uuid,
            assignmentId: AssignmentId::fromString($command->assignmentId),
            capabilityKey: $command->capabilityKey,
            isGranted: $command->isGranted,
            deletedAt: $capability->deletedAt
        );

        $this->repository->save($updatedCapability);
    }
}
