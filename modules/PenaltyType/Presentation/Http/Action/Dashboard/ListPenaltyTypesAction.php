<?php
// modules/PenaltyType/Presentation/Http/Action/Dashboard/ListPenaltyTypesAction.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\PenaltyType\Application\Query\ListPenaltyTypes\ListPenaltyTypesQuery;
use Modules\PenaltyType\Application\Query\ListPenaltyTypes\ListPenaltyTypesHandler;
use Modules\PenaltyType\Presentation\Http\Presenter\PenaltyTypePresenter;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListPenaltyTypesAction
{
    public function __construct(
        private ListPenaltyTypesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $criteria = FilterCriteria::fromArray($request->all());
        
        $penaltyTypes = $this->handler->handle(new ListPenaltyTypesQuery(
            criteria: $criteria,
            paginated: false
        ));

        return $this->responder->success(
            data: array_map(
                fn($item) => PenaltyTypePresenter::fromDomain($item),
                $penaltyTypes->all()
            )
        );
    }
}
