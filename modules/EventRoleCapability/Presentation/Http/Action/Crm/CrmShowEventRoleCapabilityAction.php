<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmShowEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmGetEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Queries\Crm\CrmGetEventRoleCapabilityQuery;
use Modules\EventRoleCapability\Presentation\Http\Presenter\CrmEventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmShowEventRoleCapabilityAction
{
    public function __construct(
        private CrmGetEventRoleCapabilityHandler $handler,
        private CrmEventRoleCapabilityPresenter $presenter,
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
