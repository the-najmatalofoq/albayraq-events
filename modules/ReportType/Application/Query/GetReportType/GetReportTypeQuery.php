<?php
// modules/ReportType/Application/Query/GetReportType/GetReportTypeQuery.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Query\GetReportType;

final readonly class GetReportTypeQuery
{
    public function __construct(
        public string $id
    ) {}
}
