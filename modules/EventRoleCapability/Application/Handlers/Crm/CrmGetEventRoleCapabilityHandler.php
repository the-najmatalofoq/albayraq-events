<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmGetEventRoleCapabilityHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Application\Queries\Crm\CrmGetEventRoleCapabilityQuery;

final readonly class CrmGetEventRoleCapabilityHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CrmGetEventRoleCapabilityQuery $query): ?EventRoleCapability
    {
        $id = CapabilityId::fromString($query->id);
        
        return $query->withIdTrashed 
            ? $this->repository->findByIdWithTrashed($id)
            : $this->repository->findById($id);
    }
}
