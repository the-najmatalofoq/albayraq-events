<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmListEventPositionApplicationsAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardListEventPositionApplicationsHandler;
use Modules\EventPositionApplication\Application\Queries\Dashboard\DashboardListEventPositionApplicationsQuery;
use Modules\EventPositionApplication\Presentation\Http\Request\Dashboard\DashboardListEventPositionApplicationsRequest;
use Modules\EventPositionApplication\Presentation\Http\Presenter\DashboardEventPositionApplicationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventPositionApplicationsAction
{
    public function __construct(
        private ListEventPositionApplicationsHandler $handler,
        private EventPositionApplicationPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ListEventPositionApplicationsRequest $request): JsonResponse
    {
        $items = $this->handler->handle(new CrmListEventPositionApplicationsQuery($request->toFilterCriteria()));
        return $this->responder->success(data: $this->presenter->presentCollection($items));
    }
}
