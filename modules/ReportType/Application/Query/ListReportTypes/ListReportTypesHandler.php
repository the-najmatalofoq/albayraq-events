<?php
// modules/ReportType/Application/Query/ListReportTypes/ListReportTypesHandler.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Query\ListReportTypes;

use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;

final readonly class ListReportTypesHandler
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository
    ) {}

    /**
     * @return array{items: \Modules\ReportType\Domain\ReportType[], total: int}
     */
    public function handle(ListReportTypesQuery $query): array
    {
        return $this->repository->paginate(
            $query->pagination,
            $query->search,
            $query->isActive
        );
    }
}
