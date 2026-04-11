<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmUpdateEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmUpdateEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmUpdateEventRoleCapabilityCommand;
use Modules\EventRoleCapability\Presentation\Http\Request\Crm\CrmUpdateEventRoleCapabilityRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmUpdateEventRoleCapabilityAction
{
    public function __construct(
        private CrmUpdateEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmUpdateEventRoleCapabilityRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(
            new CrmUpdateEventRoleCapabilityCommand(
                id: $id,
                assignmentId: $request->assignmentId(),
                capabilityKey: $request->capabilityKey(),
                isGranted: $request->isGranted()
            )
        );

        return $this->responder->success(messageKey: 'messages.capability.updated');
    }
}
