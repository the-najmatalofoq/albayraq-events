<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmCreateEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Commands\Crm\CrmCreateEventRoleAssignmentCommand;
use Modules\EventRoleAssignment\Application\Handlers\Crm\CrmCreateEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Presentation\Http\Request\Crm\CrmCreateEventRoleAssignmentRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmCreateEventRoleAssignmentAction
{
    public function __construct(
        private CrmCreateEventRoleAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CrmCreateEventRoleAssignmentRequest $request): JsonResponse
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
