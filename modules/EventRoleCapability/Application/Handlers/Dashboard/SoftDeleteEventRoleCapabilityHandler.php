<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmSoftDeleteEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Dashboard;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardSoftDeleteEventRoleCapabilityCommand;

final readonly class SoftDeleteEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(SoftDeleteEventRoleCapabilityCommand $command): void
    {
        $this->repository->delete(CapabilityId::fromString($command->id));
    }
}
