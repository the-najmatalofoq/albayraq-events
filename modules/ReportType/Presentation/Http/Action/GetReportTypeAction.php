<?php
// modules/ReportType/Presentation/Http/Action/GetReportTypeAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;


use Modules\ReportType\Presentation\Http\Presenter\ReportTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\ReportType\Application\Query\GetReportType\GetReportTypeHandler;
use Modules\ReportType\Application\Query\GetReportType\GetReportTypeQuery;

final readonly class GetReportTypeAction
{
    public function __construct(
        private GetReportTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $reportType = $this->handler->handle(new GetReportTypeQuery($id));

        return $this->responder->success(
            data: ReportTypePresenter::fromDomain($reportType)
        );
    }
}
