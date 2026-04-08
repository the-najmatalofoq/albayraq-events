<?php
// modules/WorkSchedule/Presentation/Http/Action/DeleteWorkScheduleAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Application\Command\DeleteWorkSchedule\DeleteWorkScheduleCommand;
use Modules\WorkSchedule\Application\Command\DeleteWorkSchedule\DeleteWorkScheduleHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class DeleteWorkScheduleAction
{
    public function __construct(
        private DeleteWorkScheduleHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteWorkScheduleCommand($id));

        return $this->responder->noContent();
    }
}
