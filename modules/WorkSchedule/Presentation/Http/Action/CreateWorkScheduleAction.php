<?php
// modules/WorkSchedule/Presentation/Http/Action/CreateWorkScheduleAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Application\Command\CreateWorkSchedule\CreateWorkScheduleCommand;
use Modules\WorkSchedule\Application\Command\CreateWorkSchedule\CreateWorkScheduleHandler;
use Modules\WorkSchedule\Presentation\Http\Request\CreateWorkScheduleRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class CreateWorkScheduleAction
{
    public function __construct(
        private CreateWorkScheduleHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(CreateWorkScheduleRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateWorkScheduleCommand(
            schedulableId: $request->validated('schedulable_id'),
            schedulableType: $request->validated('schedulable_type'),
            date: new \DateTimeImmutable($request->validated('date')),
            startTime: $request->validated('start_time'),
            endTime: $request->validated('end_time'),
            isActive: $request->validated('is_active', true)
        ));

        return $this->responder->created(['id' => $id->value]);
    }
}
