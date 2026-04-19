<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmCreateEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Commands\Dashboard\DashboardCreateEventRoleAssignmentCommand;
use Modules\EventRoleAssignment\Application\Handlers\Dashboard\DashboardCreateEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Presentation\Http\Request\Dashboard\DashboardCreateEventRoleAssignmentRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventRoleAssignmentAction
{
    public function __construct(
        private CreateEventRoleAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CreateEventRoleAssignmentRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CrmCreateEventRoleAssignmentCommand(
            eventId: $request->eventId(),
            userId: $request->userId(),
            roleId: $request->roleId(),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.assignment.created'
        );
    }
}
