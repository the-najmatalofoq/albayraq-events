<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmListEventRoleCapabilitiesAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardListEventRoleCapabilitiesHandler;
use Modules\EventRoleCapability\Application\Queries\Dashboard\DashboardListEventRoleCapabilitiesQuery;
use Modules\EventRoleCapability\Presentation\Http\Request\Dashboard\DashboardListEventRoleCapabilitiesRequest;
use Modules\EventRoleCapability\Presentation\Http\Presenter\DashboardEventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventRoleCapabilitiesAction
{
    public function __construct(
        private ListEventRoleCapabilitiesHandler $handler,
        private EventRoleCapabilityPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ListEventRoleCapabilitiesRequest $request): JsonResponse
    {
        $capabilities = $this->handler->handle(
            new CrmListEventRoleCapabilitiesQuery($request->toFilterCriteria())
        );

        return $this->responder->success(
            data: $this->presenter->presentCollection($capabilities)
        );
    }
}
