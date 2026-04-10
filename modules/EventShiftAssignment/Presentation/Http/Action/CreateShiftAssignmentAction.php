<?php
// modules/EventShiftAssignment/Presentation/Http/Action/CreateShiftAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventShiftAssignment\Application\Command\CreateShiftAssignment\CreateShiftAssignmentCommand;
use Modules\EventShiftAssignment\Application\Command\CreateShiftAssignment\CreateShiftAssignmentHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateShiftAssignmentAction
{
    public function __construct(
        private CreateShiftAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }
    // fix: make the (CreateShiftAssignment) formRequest for validation

    public function __invoke(Request $request, string $participationId): JsonResponse
    {
        $id = $this->handler->handle(new CreateShiftAssignmentCommand(
            participationId: $participationId,
            shiftId: $request->input('shift_id'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.shift_assignment.created',
        );
    }
}
