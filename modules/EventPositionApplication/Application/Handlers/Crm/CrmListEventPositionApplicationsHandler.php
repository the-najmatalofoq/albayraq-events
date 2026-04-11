<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmListEventPositionApplicationsHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Crm;

use Illuminate\Support\Collection;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Application\Queries\Crm\CrmListEventPositionApplicationsQuery;

final readonly class CrmListEventPositionApplicationsHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(CrmListEventPositionApplicationsQuery $query): Collection
    {
        return $this->repository->all($query->criteria);
    }
}
