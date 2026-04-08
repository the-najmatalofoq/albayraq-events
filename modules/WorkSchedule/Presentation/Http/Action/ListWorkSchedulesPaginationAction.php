<?php
// modules/WorkSchedule/Presentation/Http/Action/ListWorkSchedulesPaginationAction.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Action;

use Modules\WorkSchedule\Application\Query\ListWorkSchedules\ListWorkSchedulesQuery;
use Modules\WorkSchedule\Application\Query\ListWorkSchedules\ListWorkSchedulesHandler;
use Modules\WorkSchedule\Presentation\Http\Request\ListWorkSchedulesPaginationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Illuminate\Http\JsonResponse;
use Modules\WorkSchedule\Presentation\Http\Presenter\WorkSchedulePresenter;

final readonly class ListWorkSchedulesPaginationAction
{
    public function __construct(
        private ListWorkSchedulesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(ListWorkSchedulesPaginationRequest $request): JsonResponse
    {
        $pagination = PaginationCriteria::fromArray($request->validated());

        $result = $this->handler->handle(new ListWorkSchedulesQuery(
            pagination: $pagination,
            schedulableType: $request->validated('schedulable_type'),
            schedulableId: $request->validated('schedulable_id')
        ));

        return $this->responder->paginated(
            items: $result['items'],
            total: $result['total'],
            pagination: $pagination,
            presenter: fn($type) => WorkSchedulePresenter::fromDomain($type)
        );
    }
}
