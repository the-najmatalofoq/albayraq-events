<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmListEventRoleCapabilitiesAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmListEventRoleCapabilitiesHandler;
use Modules\EventRoleCapability\Application\Queries\Crm\CrmListEventRoleCapabilitiesQuery;
use Modules\EventRoleCapability\Presentation\Http\Request\Crm\CrmListEventRoleCapabilitiesRequest;
use Modules\EventRoleCapability\Presentation\Http\Presenter\CrmEventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListEventRoleCapabilitiesAction
{
    public function __construct(
        private CrmListEventRoleCapabilitiesHandler $handler,
        private CrmEventRoleCapabilityPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmListEventRoleCapabilitiesRequest $request): JsonResponse
    {
        $capabilities = $this->handler->handle(
            new CrmListEventRoleCapabilitiesQuery($request->toFilterCriteria())
        );

        return $this->responder->success(
            data: $this->presenter->presentCollection($capabilities)
        );
    }
}
