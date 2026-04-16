<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmCreateEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardCreateEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardCreateEventRoleCapabilityCommand;
use Modules\EventRoleCapability\Presentation\Http\Request\Dashboard\DashboardCreateEventRoleCapabilityRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventRoleCapabilityAction
{
    public function __construct(
        private CreateEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateEventRoleCapabilityRequest $request): JsonResponse
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
