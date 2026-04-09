<?php
// modules/ReportType/Presentation/Http/Action/ListReportTypesPaginationAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Application\Query\ListReportTypes\ListReportTypesHandler;
use Modules\ReportType\Application\Query\ListReportTypes\ListReportTypesQuery;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Illuminate\Http\JsonResponse;
use Modules\ReportType\Presentation\Http\Request\ListReportTypesPaginationRequest;
use Modules\ReportType\Presentation\Http\Presenter\ReportTypePresenter;

final readonly class ListReportTypesPaginationAction
{
    public function __construct(
        private ListReportTypesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(ListReportTypesPaginationRequest $request): JsonResponse
    {
        $pagination = PaginationCriteria::fromArray($request->validated());

        $query = new ListReportTypesQuery(
            pagination: $pagination
        );

        $result = $this->handler->handle($query);

        return $this->responder->paginated(
            items: $result['items'],
            total: $result['total'],
            pagination: $pagination,
            presenter: fn($type) => ReportTypePresenter::fromDomain($type)
        );
    }
}
