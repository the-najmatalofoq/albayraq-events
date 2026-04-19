<?php
// filePath: modules/EventPositionApplication/Application/Queries/Crm/CrmListPaginatedEventPositionApplicationsQuery.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Queries\Dashboard;

use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final readonly class ListPaginatedEventPositionApplicationsQuery
{
    public function __construct(
        public FilterCriteria $criteria,
        public PaginationCriteria $pagination,
    ) {}
}
