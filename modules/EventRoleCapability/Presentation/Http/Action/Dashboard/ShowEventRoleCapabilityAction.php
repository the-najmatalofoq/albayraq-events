<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmShowEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardGetEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Queries\Dashboard\DashboardGetEventRoleCapabilityQuery;
use Modules\EventRoleCapability\Presentation\Http\Presenter\DashboardEventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowEventRoleCapabilityAction
{
    public function __construct(
        private GetEventRoleCapabilityHandler $handler,
        private EventRoleCapabilityPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $capability = $this->handler->handle(
            new CrmGetEventRoleCapabilityQuery(
                id: $id,
                withIdTrashed: filter_var($request->query('trashed'), FILTER_VALIDATE_BOOLEAN)
            )
        );

        if ($capability === null) {
            return $this->responder->notFound('messages.capability.not_found');
        }

        return $this->responder->success(
            data: $this->presenter->present($capability)
        );
    }
}
