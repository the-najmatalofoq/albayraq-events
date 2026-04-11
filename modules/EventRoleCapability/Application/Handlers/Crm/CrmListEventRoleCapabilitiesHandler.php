<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmListEventRoleCapabilitiesHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Illuminate\Support\Collection;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Application\Queries\Crm\CrmListEventRoleCapabilitiesQuery;

final readonly class CrmListEventRoleCapabilitiesHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CrmListEventRoleCapabilitiesQuery $query): Collection
    {
        return $this->repository->all($query->criteria);
    }
}
