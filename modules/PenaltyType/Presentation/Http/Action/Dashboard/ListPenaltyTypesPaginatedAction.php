<?php
// modules/PenaltyType/Presentation/Http/Action/Dashboard/ListPenaltyTypesPaginatedAction.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\PenaltyType\Application\Query\ListPenaltyTypes\ListPenaltyTypesQuery;
use Modules\PenaltyType\Application\Query\ListPenaltyTypes\ListPenaltyTypesHandler;
use Modules\PenaltyType\Presentation\Http\Presenter\PenaltyTypePresenter;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListPenaltyTypesPaginatedAction
{
    public function __construct(
        private ListPenaltyTypesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $criteria = FilterCriteria::fromArray($request->all());
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->handler->handle(new ListPenaltyTypesQuery(
            criteria: $criteria,
            perPage: $perPage,
            paginated: true
        ));

        return $this->responder->success(
            data: [
                'items' => $paginator->getCollection()->map(
                    fn($item) => PenaltyTypePresenter::fromDomain($item)
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
