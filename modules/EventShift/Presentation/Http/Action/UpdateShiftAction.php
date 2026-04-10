<?php
// modules/EventShift/Presentation/Http/Action/UpdateShiftAction.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventShift\Application\Command\UpdateShift\UpdateShiftCommand;
use Modules\EventShift\Application\Command\UpdateShift\UpdateShiftHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateShiftAction
{
    public function __construct(
        private UpdateShiftHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    // fix: make the (UpdateShiftAction) formRequest for validation
    public function __invoke(Request $request, string $eventId, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateShiftCommand(
            shiftId: $id,
            label: $request->input('label'),
            startAt: $request->input('start_at'),
            endAt: $request->input('end_at'),
            maxAssignees: $request->input('max_assignees'),
        ));

        return $this->responder->success(messageKey: 'messages.shift.updated');
    }
}
