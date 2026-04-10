<?php
// modules/EventStaffingGroup/Presentation/Http/Action/DeleteEventStaffingGroupAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingGroup\Application\Command\DeleteGroup\DeleteGroupHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteEventStaffingGroupAction
{
    public function __construct(
        private DeleteGroupHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->handler->handle($id);

        return $this->responder->noContent();
    }
}
