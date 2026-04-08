<?php
// modules/ViolationType/Presentation/Http/Action/ListViolationTypesPaginationAction.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action;

use Modules\ViolationType\Application\Query\ListViolationTypes\ListViolationTypesQuery;
use Modules\ViolationType\Application\Query\ListViolationTypes\ListViolationTypesHandler;
use Modules\ViolationType\Presentation\Http\Request\ListViolationTypesPaginationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Illuminate\Http\JsonResponse;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;

final readonly class ListViolationTypesPaginationAction
{
    public function __construct(
        private ListViolationTypesHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(ListViolationTypesPaginationRequest $request): JsonResponse
    {
        $pagination = PaginationCriteria::fromArray($request->validated());

        $result = $this->handler->handle(new ListViolationTypesQuery(
            pagination: $pagination,
            search: $request->validated('search')
        ));

        return $this->responder->paginated(
            items: $result['items'],
            total: $result['total'],
            pagination: $pagination,
            presenter: fn($type) => ViolationTypePresenter::fromDomain($type)
        );
    }
}
