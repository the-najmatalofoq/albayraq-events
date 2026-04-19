<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmListEventRoleAssignmentsAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Handlers\Dashboard\DashboardListEventRoleAssignmentsHandler;
use Modules\EventRoleAssignment\Application\Queries\Dashboard\DashboardListEventRoleAssignmentsQuery;
use Modules\EventRoleAssignment\Presentation\Http\Request\Dashboard\DashboardListEventRoleAssignmentsRequest;
use Modules\EventRoleAssignment\Presentation\Http\Presenter\DashboardEventRoleAssignmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventRoleAssignmentsAction
{
    public function __construct(
        private ListEventRoleAssignmentsHandler $handler,
        private EventRoleAssignmentPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ListEventRoleAssignmentsRequest $request): JsonResponse
    {
        $assignments = $this->handler->handle(new CrmListEventRoleAssignmentsQuery(
            eventId: $request->eventId()
        ));

        return $this->responder->success(
            data: $this->presenter->presentCollection($assignments)
        );
    }
}
