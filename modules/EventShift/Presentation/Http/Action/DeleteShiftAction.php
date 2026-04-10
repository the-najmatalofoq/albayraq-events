<?php
// modules/EventShift/Presentation/Http/Action/DeleteShiftAction.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventShift\Application\Command\DeleteShift\DeleteShiftHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteShiftAction
{
    public function __construct(
        private DeleteShiftHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->handler->handle($id);
        return $this->responder->noContent();
    }
}
