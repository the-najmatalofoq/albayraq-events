<?php
// modules/ReportType/Application/Query/ListReportTypes/ListReportTypesQuery.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Query\ListReportTypes;

use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final readonly class ListReportTypesQuery
{
    public function __construct(
        public PaginationCriteria $pagination,
        public ?string $search = null,
        public ?bool $isActive = null   
    ) {}
}
