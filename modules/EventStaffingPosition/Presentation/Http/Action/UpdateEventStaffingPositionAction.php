<?php
// modules/EventStaffingPosition/Presentation/Http/Action/UpdateEventStaffingPositionAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventStaffingPosition\Application\Command\UpdatePosition\UpdatePositionCommand;
use Modules\EventStaffingPosition\Application\Command\UpdatePosition\UpdatePositionHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateEventStaffingPositionAction
{
    public function __construct(
        private UpdatePositionHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $eventId, string $id): JsonResponse
    {
        $this->handler->handle(new UpdatePositionCommand(
            id: $id,
            title: $request->input('title'),
            wageAmount: (float) $request->input('wage_amount'),
            wageType: $request->input('wage_type'),
            headcount: (int) $request->input('headcount'),
            requirements: $request->input('requirements'),
        ));

        return $this->responder->success(
            messageKey: 'messages.position.updated'
        );
    }
}
