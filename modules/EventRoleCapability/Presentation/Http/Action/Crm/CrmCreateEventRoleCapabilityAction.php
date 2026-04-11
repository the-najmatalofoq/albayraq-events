<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmCreateEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Crm\CrmCreateEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Crm\CrmCreateEventRoleCapabilityCommand;
use Modules\EventRoleCapability\Presentation\Http\Request\Crm\CrmCreateEventRoleCapabilityRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmCreateEventRoleCapabilityAction
{
    public function __construct(
        private CrmCreateEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmCreateEventRoleCapabilityRequest $request): JsonResponse
    {
        $id = $this->handler->handle(
            new CrmCreateEventRoleCapabilityCommand(
                assignmentId: $request->assignmentId(),
                capabilityKey: $request->capabilityKey(),
                isGranted: $request->isGranted()
            )
        );

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.capability.created'
        );
    }
}
