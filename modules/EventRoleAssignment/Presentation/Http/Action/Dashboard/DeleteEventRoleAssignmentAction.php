<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmDeleteEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Commands\Dashboard\DashboardDeleteEventRoleAssignmentCommand;
use Modules\EventRoleAssignment\Application\Handlers\Dashboard\DashboardDeleteEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Presentation\Http\Request\Dashboard\DashboardDeleteEventRoleAssignmentRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteEventRoleAssignmentAction
{
    public function __construct(
        private DeleteEventRoleAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(DeleteEventRoleAssignmentRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new CrmDeleteEventRoleAssignmentCommand($id));

        return $this->responder->noContent();
    }
}
