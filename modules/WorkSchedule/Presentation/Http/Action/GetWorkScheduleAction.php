<?php
// modules/WorkSchedule/Presentation/Http/Action/GetWorkScheduleAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Application\Query\GetWorkSchedule\GetWorkScheduleQuery;
use Modules\WorkSchedule\Application\Query\GetWorkSchedule\GetWorkScheduleHandler;
use Modules\WorkSchedule\Presentation\Http\Presenter\WorkSchedulePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class GetWorkScheduleAction
{
    public function __construct(
        private GetWorkScheduleHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $workSchedule = $this->handler->handle(new GetWorkScheduleQuery($id));

        return $this->responder->success(
            data: WorkSchedulePresenter::fromDomain($workSchedule)
        );
    }
}
