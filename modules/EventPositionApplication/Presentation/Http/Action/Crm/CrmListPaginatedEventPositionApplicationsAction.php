<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmListPaginatedEventPositionApplicationsAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmListPaginatedEventPositionApplicationsHandler;
use Modules\EventPositionApplication\Application\Queries\Crm\CrmListPaginatedEventPositionApplicationsQuery;
use Modules\EventPositionApplication\Presentation\Http\Request\Crm\CrmListEventPositionApplicationsRequest;
use Modules\EventPositionApplication\Presentation\Http\Presenter\CrmEventPositionApplicationPresenter;
use Modules\EventPositionApplication\Infrastructure\Persistence\EventPositionApplicationReflector;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListPaginatedEventPositionApplicationsAction
{
    public function __construct(
        private CrmListPaginatedEventPositionApplicationsHandler $handler,
        private CrmEventPositionApplicationPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmListEventPositionApplicationsRequest $request): JsonResponse
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
