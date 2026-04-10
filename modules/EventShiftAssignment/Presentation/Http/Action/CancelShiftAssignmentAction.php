<?php
// modules/EventShiftAssignment/Presentation/Http/Action/CancelShiftAssignmentAction.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventShiftAssignment\Application\Command\CancelShiftAssignment\CancelShiftAssignmentCommand;
use Modules\EventShiftAssignment\Application\Command\CancelShiftAssignment\CancelShiftAssignmentHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CancelShiftAssignmentAction
{
    public function __construct(
        private CancelShiftAssignmentHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $participationId, string $id): JsonResponse
    {
        $this->handler->handle(new CancelShiftAssignmentCommand(assignmentId: $id));
        return $this->responder->noContent();
    }
}
