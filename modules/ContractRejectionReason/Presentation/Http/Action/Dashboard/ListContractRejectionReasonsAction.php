<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Presentation\Http\Presenter\ContractRejectionReasonPresenter;
use Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard\ContractRejectionReasonFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListContractRejectionReasonsAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(ContractRejectionReasonFilterRequest $request): mixed
    {
        $criteria = $request->toFilterCriteria();
        $reasons = $this->repository->all($criteria);
        
        return $this->responder->success(
            data: $reasons->map(fn($reason) => ContractRejectionReasonPresenter::fromDomain($reason))->toArray()
        );
    }
}
