<?php
// modules/EventStaffingGroup/Presentation/Http/Action/CreateEventStaffingGroupAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventStaffingGroup\Application\Command\CreateGroup\CreateGroupCommand;
use Modules\EventStaffingGroup\Application\Command\CreateGroup\CreateGroupHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventStaffingGroupAction
{
    public function __construct(
        private CreateGroupHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    // fix: make the (CreateEventStaffingGroup) formRequest for validation

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $id = $this->handler->handle(new CreateGroupCommand(
            eventId: $eventId,
            name: $request->input('name'),
            color: $request->input('color'),
            isLocked: (bool) $request->input('is_locked', false),
            leaderId: $request->input('leader_id'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.group.created'
        );
    }
}
