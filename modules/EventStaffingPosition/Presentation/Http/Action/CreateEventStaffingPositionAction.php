<?php
// modules/EventStaffingPosition/Presentation/Http/Action/CreateEventStaffingPositionAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventStaffingPosition\Application\Command\CreatePosition\CreatePositionCommand;
use Modules\EventStaffingPosition\Application\Command\CreatePosition\CreatePositionHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventStaffingPositionAction
{
    public function __construct(
        private CreatePositionHandler $handler,
        private JsonResponder $responder,
    ) {
    }
    // fix: make the (CreateEventStaffingPosition) formRequest for validation

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $id = $this->handler->handle(new CreatePositionCommand(
            eventId: $eventId,
            title: $request->input('title'),
            wageAmount: (float) $request->input('wage_amount'),
            wageType: $request->input('wage_type'),
            headcount: (int) $request->input('headcount'),
            requirements: $request->input('requirements'),
            isAnnounced: (bool) $request->input('is_announced', false),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.position.created'
        );
    }
}
