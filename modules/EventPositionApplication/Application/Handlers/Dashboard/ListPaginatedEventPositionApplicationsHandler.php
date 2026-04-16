<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmListPaginatedEventPositionApplicationsHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Application\Queries\Dashboard\DashboardListPaginatedEventPositionApplicationsQuery;

final readonly class ListPaginatedEventPositionApplicationsHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(ListPaginatedEventPositionApplicationsQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate($query->criteria, $query->pagination->perPage);
    }
}
