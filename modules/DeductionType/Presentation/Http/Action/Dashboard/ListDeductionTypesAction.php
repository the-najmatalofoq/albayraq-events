<?php
// modules/DeductionType/Presentation/Http/Action/Dashboard/ListDeductionTypesAction.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\DeductionType\Application\Query\ListDeductionTypes\ListDeductionTypesQuery;
use Modules\DeductionType\Application\Query\ListDeductionTypes\ListDeductionTypesHandler;
use Modules\DeductionType\Presentation\Http\Presenter\DeductionTypePresenter;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListDeductionTypesAction
{
    public function __construct(
        private ListDeductionTypesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $criteria = FilterCriteria::fromArray($request->all());

        $deductionTypes = $this->handler->handle(new ListDeductionTypesQuery(
            criteria: $criteria,
            paginated: false
        ));

        return $this->responder->success(
            data: array_map(
                fn($item) => DeductionTypePresenter::fromDomain($item),
                $deductionTypes->all()
            )
        );
    }
}
