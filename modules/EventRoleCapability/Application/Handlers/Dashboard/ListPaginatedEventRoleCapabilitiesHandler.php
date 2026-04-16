<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmListPaginatedEventRoleCapabilitiesHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Dashboard;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Application\Queries\Dashboard\DashboardListPaginatedEventRoleCapabilitiesQuery;

final readonly class ListPaginatedEventRoleCapabilitiesHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(ListPaginatedEventRoleCapabilitiesQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $query->criteria,
            $query->pagination->perPage
        );
    }
}
