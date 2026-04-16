<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmListEventRoleCapabilitiesHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Dashboard;

use Illuminate\Support\Collection;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Application\Queries\Dashboard\DashboardListEventRoleCapabilitiesQuery;

final readonly class ListEventRoleCapabilitiesHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(ListEventRoleCapabilitiesQuery $query): Collection
    {
        return $this->repository->all($query->criteria);
    }
}
