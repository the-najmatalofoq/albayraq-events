<?php
// filePath: modules/EventPositionApplication/Application/Queries/Crm/CrmListEventPositionApplicationsQuery.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Queries\Crm;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class CrmListEventPositionApplicationsQuery
{
    public function __construct(public FilterCriteria $criteria) {}
}
