<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmListPaginatedEventRoleCapabilitiesAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardListPaginatedEventRoleCapabilitiesHandler;
use Modules\EventRoleCapability\Application\Queries\Dashboard\DashboardListPaginatedEventRoleCapabilitiesQuery;
use Modules\EventRoleCapability\Infrastructure\Persistence\EventRoleCapabilityReflector;
use Modules\EventRoleCapability\Presentation\Http\Request\Dashboard\DashboardListEventRoleCapabilitiesRequest;
use Modules\EventRoleCapability\Presentation\Http\Presenter\DashboardEventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListPaginatedEventRoleCapabilitiesAction
{
    public function __construct(
        private ListPaginatedEventRoleCapabilitiesHandler $handler,
        private EventRoleCapabilityPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ListEventRoleCapabilitiesRequest $request): JsonResponse
    {
        $paginator = $this->handler->handle(
            new CrmListPaginatedEventRoleCapabilitiesQuery(
                $request->toFilterCriteria(),
                $request->toPaginationCriteria()
            )
        );

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($item) => $this->presenter->present(EventRoleCapabilityReflector::fromModel($item))
        );
    }
}
