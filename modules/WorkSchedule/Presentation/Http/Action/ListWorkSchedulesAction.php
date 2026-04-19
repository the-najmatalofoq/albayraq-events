<?php
// modules/WorkSchedule/Presentation/Http/Action/ListWorkSchedulesAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\WorkSchedule\Presentation\Http\Request\WorkScheduleFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\WorkSchedule\Presentation\Http\Presenter\WorkSchedulePresenter;

final readonly class ListWorkSchedulesAction
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(WorkScheduleFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $schedules = $this->repository->all($criteria);

        return $this->responder->success(
            $schedules->map(fn($schedule) => WorkSchedulePresenter::fromDomain($schedule))->toArray()
        );
    }
}
