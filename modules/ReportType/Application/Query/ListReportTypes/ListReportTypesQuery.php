<?php
// modules/ReportType/Application/Query/ListReportTypes/ListReportTypesQuery.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Query\ListReportTypes;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class ListReportTypesQuery
{
    public function __construct(
        public FilterCriteria $criteria
    ) {}
}
