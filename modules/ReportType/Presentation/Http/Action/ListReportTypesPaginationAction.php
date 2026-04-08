<?php
// modules\ReportType\Presentation\Http\Action\ListReportTypesPaginationAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Application\Query\ListReportTypes\ListReportTypesHandler;
use Modules\ReportType\Application\Query\ListReportTypes\ListReportTypesQuery;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\ReportType\Presentation\Http\Request\ListReportTypesPaginationRequest;
use Modules\ReportType\Presentation\Http\Presenter\ReportTypePresenter;

final readonly class ListReportTypesPaginationAction
{
    public function __construct(
        private ListReportTypesHandler $handler,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(ListReportTypesPaginationRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $query = new ListReportTypesQuery(
            criteria: $criteria
        );

        $paginator = $this->handler->handle($query);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($type) => ReportTypePresenter::fromDomain($type)
        );
    }
}
