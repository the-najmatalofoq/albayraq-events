<?php
// modules/WorkSchedule/Presentation/Http/Action/UpdateWorkScheduleAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Application\Command\UpdateWorkSchedule\UpdateWorkScheduleCommand;
use Modules\WorkSchedule\Application\Command\UpdateWorkSchedule\UpdateWorkScheduleHandler;
use Modules\WorkSchedule\Presentation\Http\Request\UpdateWorkScheduleRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class UpdateWorkScheduleAction
{
    public function __construct(
        private UpdateWorkScheduleHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id, UpdateWorkScheduleRequest $request): JsonResponse
    {
        $this->handler->handle(new UpdateWorkScheduleCommand(
            id: $id,
            date: $request->has('date') ? new \DateTimeImmutable($request->validated('date')) : null,
            startTime: $request->validated('start_time'),
            endTime: $request->validated('end_time'),
            isActive: $request->validated('is_active')
        ));

        return $this->responder->success(messageKey: 'messages.updated');
    }
}
