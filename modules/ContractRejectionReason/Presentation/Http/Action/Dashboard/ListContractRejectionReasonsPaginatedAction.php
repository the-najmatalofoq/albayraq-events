<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Presentation\Http\Presenter\ContractRejectionReasonPresenter;
use Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard\ContractRejectionReasonFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListContractRejectionReasonsPaginatedAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(ContractRejectionReasonFilterRequest $request): mixed
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->repository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($reason) => ContractRejectionReasonPresenter::fromDomain($reason)
        );
    }
}
