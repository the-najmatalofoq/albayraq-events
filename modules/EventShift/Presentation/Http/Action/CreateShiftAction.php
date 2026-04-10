<?php
// modules/EventShift/Presentation/Http/Action/CreateShiftAction.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventShift\Application\Command\CreateShift\CreateShiftCommand;
use Modules\EventShift\Application\Command\CreateShift\CreateShiftHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateShiftAction
{
    public function __construct(
        private CreateShiftHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    // create (CreateShiftAction) FormRequest for validation
    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $id = $this->handler->handle(new CreateShiftCommand(
            eventId: $eventId,
            positionId: $request->input('position_id'),
            label: $request->input('label'),
            startAt: $request->input('start_at'),
            endAt: $request->input('end_at'),
            maxAssignees: $request->input('max_assignees'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.shift.created',
        );
    }
}
