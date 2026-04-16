<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmListPaginatedEventPositionApplicationsAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardListPaginatedEventPositionApplicationsHandler;
use Modules\EventPositionApplication\Application\Queries\Dashboard\DashboardListPaginatedEventPositionApplicationsQuery;
use Modules\EventPositionApplication\Presentation\Http\Request\Dashboard\DashboardListEventPositionApplicationsRequest;
use Modules\EventPositionApplication\Presentation\Http\Presenter\DashboardEventPositionApplicationPresenter;
use Modules\EventPositionApplication\Infrastructure\Persistence\EventPositionApplicationReflector;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListPaginatedEventPositionApplicationsAction
{
    public function __construct(
        private ListPaginatedEventPositionApplicationsHandler $handler,
        private EventPositionApplicationPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ListEventPositionApplicationsRequest $request): JsonResponse
    {
        $paginator = $this->handler->handle(
            new CrmListPaginatedEventPositionApplicationsQuery($request->toFilterCriteria(), $request->toPaginationCriteria())
        );
        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($item) => $this->presenter->present(EventPositionApplicationReflector::fromModel($item))
        );
    }
}
