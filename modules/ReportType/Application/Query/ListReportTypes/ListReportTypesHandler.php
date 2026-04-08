<?php
// modules/ReportType/Application/Query/ListReportTypes/ListReportTypesHandler.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Query\ListReportTypes;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;

final readonly class ListReportTypesHandler
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository
    ) {
    }

    public function handle(ListReportTypesQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $query->criteria,
            $query->criteria->perPage ?? 15
        );
    }
}
