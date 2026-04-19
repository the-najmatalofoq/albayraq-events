<?php
// modules/WorkSchedule/Presentation/Http/Action/ListWorkSchedulesPaginatedAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\WorkSchedule\Presentation\Http\Request\WorkScheduleFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\WorkSchedule\Presentation\Http\Presenter\WorkSchedulePresenter;

final readonly class ListWorkSchedulesPaginatedAction
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(WorkScheduleFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->repository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($schedule) => WorkSchedulePresenter::fromDomain($schedule)
        );
    }
}
