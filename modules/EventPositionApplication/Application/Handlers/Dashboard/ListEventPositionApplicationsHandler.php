<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmListEventPositionApplicationsHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Illuminate\Support\Collection;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Application\Queries\Dashboard\DashboardListEventPositionApplicationsQuery;

final readonly class ListEventPositionApplicationsHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(ListEventPositionApplicationsQuery $query): Collection
    {
        return $this->repository->all($query->criteria);
    }
}
