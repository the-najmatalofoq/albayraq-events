<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmListPaginatedEventPositionApplicationsHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Crm;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Application\Queries\Crm\CrmListPaginatedEventPositionApplicationsQuery;

final readonly class CrmListPaginatedEventPositionApplicationsHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(CrmListPaginatedEventPositionApplicationsQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate($query->criteria, $query->pagination->perPage);
    }
}
