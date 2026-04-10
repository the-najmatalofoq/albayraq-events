<?php
// modules/EventShiftAssignment/Presentation/Http/Action/AssignToShiftAction.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventShiftAssignment\Application\Command\AssignToShift\AssignToShiftCommand;
use Modules\EventShiftAssignment\Application\Command\AssignToShift\AssignToShiftHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class AssignToShiftAction
{
    public function __construct(
        private AssignToShiftHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    // fix: make the (AssignToShiftAction) formRequest for validation
    public function __invoke(Request $request, string $eventId, string $shiftId): JsonResponse
    {
        $id = $this->handler->handle(new AssignToShiftCommand(
            shiftId: $shiftId,
            participationId: $request->input('participation_id'),
            assignedBy: $request->user()->id,
            notes: $request->input('notes'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.shift_assignment.created',
        );
    }
}
