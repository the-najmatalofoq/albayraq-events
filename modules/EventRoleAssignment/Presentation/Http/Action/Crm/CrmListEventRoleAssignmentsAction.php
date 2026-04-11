<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmListEventRoleAssignmentsAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Handlers\Crm\CrmListEventRoleAssignmentsHandler;
use Modules\EventRoleAssignment\Application\Queries\Crm\CrmListEventRoleAssignmentsQuery;
use Modules\EventRoleAssignment\Presentation\Http\Request\Crm\CrmListEventRoleAssignmentsRequest;
use Modules\EventRoleAssignment\Presentation\Http\Presenter\CrmEventRoleAssignmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListEventRoleAssignmentsAction
{
    public function __construct(
        private CrmListEventRoleAssignmentsHandler $handler,
        private CrmEventRoleAssignmentPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CrmListEventRoleAssignmentsRequest $request): JsonResponse
    {
        $assignments = $this->handler->handle(new CrmListEventRoleAssignmentsQuery(
            eventId: $request->eventId()
        ));

        return $this->responder->success(
            data: $this->presenter->presentCollection($assignments)
        );
    }
}
