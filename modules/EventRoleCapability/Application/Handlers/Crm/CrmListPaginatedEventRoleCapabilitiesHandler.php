<?php
// modules/EventRoleCapability/Application/Handlers/Crm/CrmListPaginatedEventRoleCapabilitiesHandler.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Handlers\Crm;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Application\Queries\Crm\CrmListPaginatedEventRoleCapabilitiesQuery;

final readonly class CrmListPaginatedEventRoleCapabilitiesHandler
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
    ) {}

    public function handle(CrmListPaginatedEventRoleCapabilitiesQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $query->criteria,
            $query->pagination->perPage
        );
    }
}
