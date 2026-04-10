<?php
// modules/EventStaffingGroup/Presentation/Http/Action/UpdateEventStaffingGroupAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventStaffingGroup\Application\Command\UpdateGroup\UpdateGroupCommand;
use Modules\EventStaffingGroup\Application\Command\UpdateGroup\UpdateGroupHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateEventStaffingGroupAction
{
    public function __construct(
        private UpdateGroupHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $eventId, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateGroupCommand(
            id: $id,
            name: $request->input('name'),
            color: $request->input('color'),
            isLocked: (bool) $request->input('is_locked'),
            leaderId: $request->input('leader_id'),
        ));

        return $this->responder->success(
            messageKey: 'messages.group.updated'
        );
    }
}
