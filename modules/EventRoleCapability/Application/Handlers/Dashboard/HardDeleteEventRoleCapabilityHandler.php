<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmHardDeleteEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Dashboard;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardHardDeleteEventRoleCapabilityCommand;

final readonly class HardDeleteEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(HardDeleteEventRoleCapabilityCommand $command): void
    {
        $this->repository->hardDelete(CapabilityId::fromString($command->id));
    }
}
