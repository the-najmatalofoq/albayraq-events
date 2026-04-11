<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmUpdateEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Commands\Crm\CrmUpdateEventRoleAssignmentCommand;
use Modules\EventRoleAssignment\Application\Handlers\Crm\CrmUpdateEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Presentation\Http\Request\Crm\CrmUpdateEventRoleAssignmentRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmUpdateEventRoleAssignmentAction
{
    public function __construct(
        private CrmUpdateEventRoleAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CrmUpdateEventRoleAssignmentRequest $request, string $id): JsonResponse
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
