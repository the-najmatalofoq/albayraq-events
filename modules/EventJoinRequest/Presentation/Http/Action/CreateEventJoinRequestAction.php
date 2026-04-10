<?php
// modules/EventJoinRequest/Presentation/Http/Action/CreateEventJoinRequestAction.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventJoinRequest\Application\Command\CreateJoinRequest\CreateJoinRequestCommand;
use Modules\EventJoinRequest\Application\Command\CreateJoinRequest\CreateJoinRequestHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventJoinRequestAction
{
    public function __construct(
        private CreateJoinRequestHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $id = $this->handler->handle(new CreateJoinRequestCommand(
            userId: (string) $request->user()->id,
            eventId: $eventId,
            positionId: $request->input('position_id'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.join_request.created',
        );
    }
}
