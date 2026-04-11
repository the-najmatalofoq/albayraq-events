<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmSoftDeleteEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmSoftDeleteEventRoleCapabilityCommand;

final readonly class CrmSoftDeleteEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CrmSoftDeleteEventRoleCapabilityCommand $command): void
    {
        $this->repository->delete(CapabilityId::fromString($command->id));
    }
}
