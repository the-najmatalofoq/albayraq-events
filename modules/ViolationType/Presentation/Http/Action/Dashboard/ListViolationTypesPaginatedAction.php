<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Presentation\Http\Presenter\ViolationTypePresenter;
use Modules\ViolationType\Presentation\Http\Request\Dashboard\ViolationTypeFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListViolationTypesPaginatedAction
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(ViolationTypeFilterRequest $request): mixed
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->repository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($type) => ViolationTypePresenter::fromDomain($type)
        );
    }
}
