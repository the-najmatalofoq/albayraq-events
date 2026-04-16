<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmRestoreEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Dashboard;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardRestoreEventRoleCapabilityCommand;

final readonly class RestoreEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {
    }

    public function handle(RestoreEventRoleCapabilityCommand $command): void
    {
        $this->repository->restore(CapabilityId::fromString($command->id));
    }
}
