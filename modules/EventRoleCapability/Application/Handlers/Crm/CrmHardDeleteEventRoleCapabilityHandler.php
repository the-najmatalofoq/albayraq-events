<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmHardDeleteEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmHardDeleteEventRoleCapabilityCommand;

final readonly class CrmHardDeleteEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CrmHardDeleteEventRoleCapabilityCommand $command): void
    {
        $this->repository->hardDelete(CapabilityId::fromString($command->id));
    }
}
