<?php
// modules/EventRoleCapability/Application/Queries/Crm/CrmListPaginatedEventRoleCapabilitiesQuery.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Queries\Dashboard;

use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final readonly class ListPaginatedEventRoleCapabilitiesQuery
{
    public function __construct(
        public FilterCriteria $criteria,
        public PaginationCriteria $pagination,
    ) {}
}
