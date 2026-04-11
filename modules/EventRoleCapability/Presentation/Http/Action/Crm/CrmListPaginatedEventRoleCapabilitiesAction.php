<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmListPaginatedEventRoleCapabilitiesAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmListPaginatedEventRoleCapabilitiesHandler;
use Modules\EventRoleCapability\Application\Queries\Crm\CrmListPaginatedEventRoleCapabilitiesQuery;
use Modules\EventRoleCapability\Infrastructure\Persistence\EventRoleCapabilityReflector;
use Modules\EventRoleCapability\Presentation\Http\Request\Crm\CrmListEventRoleCapabilitiesRequest;
use Modules\EventRoleCapability\Presentation\Http\Presenter\CrmEventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListPaginatedEventRoleCapabilitiesAction
{
    public function __construct(
        private CrmListPaginatedEventRoleCapabilitiesHandler $handler,
        private CrmEventRoleCapabilityPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CrmListEventRoleCapabilitiesRequest $request): JsonResponse
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
