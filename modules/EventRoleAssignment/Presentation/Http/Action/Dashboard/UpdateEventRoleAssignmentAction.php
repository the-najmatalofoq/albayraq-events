<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmUpdateEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Commands\Dashboard\DashboardUpdateEventRoleAssignmentCommand;
use Modules\EventRoleAssignment\Application\Handlers\Dashboard\DashboardUpdateEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Presentation\Http\Request\Dashboard\DashboardUpdateEventRoleAssignmentRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateEventRoleAssignmentAction
{
    public function __construct(
        private UpdateEventRoleAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(UpdateEventRoleAssignmentRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new CrmUpdateEventRoleAssignmentCommand(
            id: $id,
            eventId: $request->eventId(),
            userId: $request->userId(),
            roleId: $request->roleId(),
        ));

        return $this->responder->success(messageKey: 'messages.assignment.updated');
    }
}
