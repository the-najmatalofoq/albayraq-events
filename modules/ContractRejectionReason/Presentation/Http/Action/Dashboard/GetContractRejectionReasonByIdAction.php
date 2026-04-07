<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\ContractRejectionReason\Presentation\Http\Presenter\ContractRejectionReasonPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetContractRejectionReasonByIdAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): mixed
    {
        $reason = $this->repository->findById(ContractRejectionReasonId::fromString($id));

        if (!$reason) {
            return $this->responder->notFound('messages.contract_rejection_reason_not_found');
        }

        return $this->responder->success(ContractRejectionReasonPresenter::fromDomain($reason));
    }
}
