<?php
// modules/EventRoleAssignment/Presentation/Http/Action/Crm/CrmShowEventRoleAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventRoleAssignment\Application\Handlers\Crm\CrmGetEventRoleAssignmentHandler;
use Modules\EventRoleAssignment\Application\Queries\Crm\CrmGetEventRoleAssignmentQuery;
use Modules\EventRoleAssignment\Presentation\Http\Presenter\CrmEventRoleAssignmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmShowEventRoleAssignmentAction
{
    public function __construct(
        private CrmGetEventRoleAssignmentHandler $handler,
        private CrmEventRoleAssignmentPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $assignment = $this->handler->handle(new CrmGetEventRoleAssignmentQuery($id));

        if ($assignment === null) {
            return $this->responder->notFound('messages.assignment.not_found');
        }

        return $this->responder->success(
            data: $this->presenter->present($assignment)
        );
    }
}
