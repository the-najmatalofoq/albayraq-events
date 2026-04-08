<?php
// modules/ReportType/Application/Query/GetReportType/GetReportTypeHandler.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Query\GetReportType;

use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\Exception\ReportTypeNotFoundException;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;

final readonly class GetReportTypeHandler
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository
    ) {}

    public function handle(GetReportTypeQuery $query): ReportType
    {
        $id = ReportTypeId::fromString($query->id);
        $reportType = $this->repository->findById($id);

        if (!$reportType) {
            throw ReportTypeNotFoundException::withId($id);
        }

        return $reportType;
    }
}
