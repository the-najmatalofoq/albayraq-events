<?php
// modules/EventRoleCapability/Presentation/Http/Action/Crm/CrmUpdateEventRoleCapabilityAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleCapability\Application\Handlers\Dashboard\DashboardUpdateEventRoleCapabilityHandler;
use Modules\EventRoleCapability\Application\Commands\Dashboard\DashboardUpdateEventRoleCapabilityCommand;
use Modules\EventRoleCapability\Presentation\Http\Request\Dashboard\DashboardUpdateEventRoleCapabilityRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateEventRoleCapabilityAction
{
    public function __construct(
        private UpdateEventRoleCapabilityHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateEventRoleCapabilityRequest $request, string $id): JsonResponse
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
