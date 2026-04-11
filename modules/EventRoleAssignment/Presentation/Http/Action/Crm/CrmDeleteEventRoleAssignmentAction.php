<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmDeleteEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Commands\Crm\CrmDeleteEventRoleAssignmentCommand;
use Modules\EventRoleAssignment\Application\Handlers\Crm\CrmDeleteEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Presentation\Http\Request\Crm\CrmDeleteEventRoleAssignmentRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmDeleteEventRoleAssignmentAction
{
    public function __construct(
        private CrmDeleteEventRoleAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CrmDeleteEventRoleAssignmentRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new CrmDeleteEventRoleAssignmentCommand($id));

        return $this->responder->noContent();
    }
}
