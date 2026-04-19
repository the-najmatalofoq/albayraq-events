<?php
// filePath: modules/EventPositionApplication/Application/Queries/Crm/CrmListEventPositionApplicationsQuery.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Queries\Dashboard;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class ListEventPositionApplicationsQuery
{
    public function __construct(public FilterCriteria $criteria) {}
}
