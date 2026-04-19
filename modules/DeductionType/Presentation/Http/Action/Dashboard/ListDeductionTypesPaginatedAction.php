<?php
// modules/DeductionType/Presentation/Http/Action/Dashboard/ListDeductionTypesPaginatedAction.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\DeductionType\Application\Query\ListDeductionTypes\ListDeductionTypesQuery;
use Modules\DeductionType\Application\Query\ListDeductionTypes\ListDeductionTypesHandler;
use Modules\DeductionType\Presentation\Http\Presenter\DeductionTypePresenter;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListDeductionTypesPaginatedAction
{
    public function __construct(
        private ListDeductionTypesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $criteria = FilterCriteria::fromArray($request->all());
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->handler->handle(new ListDeductionTypesQuery(
            criteria: $criteria,
            perPage: $perPage,
            paginated: true
        ));

        return $this->responder->success(
            data: [
                'items' => $paginator->getCollection()->map(
                    fn($item) => DeductionTypePresenter::fromDomain($item)
                ),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ]
        );
    }
}
