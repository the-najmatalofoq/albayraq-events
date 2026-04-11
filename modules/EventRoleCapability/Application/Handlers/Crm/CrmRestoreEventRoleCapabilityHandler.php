<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmRestoreEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmRestoreEventRoleCapabilityCommand;

final readonly class CrmRestoreEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {
    }

    public function handle(CrmRestoreEventRoleCapabilityCommand $command): void
    {
        $this->repository->restore(CapabilityId::fromString($command->id));
    }
}
