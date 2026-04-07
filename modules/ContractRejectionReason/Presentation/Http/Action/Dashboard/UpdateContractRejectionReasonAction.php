<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\ContractRejectionReason\Presentation\Http\Presenter\ContractRejectionReasonPresenter;
use Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard\UpdateContractRejectionReasonRequest;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateContractRejectionReasonAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(UpdateContractRejectionReasonRequest $request, string $id): mixed
    {
        $reasonId = ContractRejectionReasonId::fromString($id);
        $reason = $this->repository->findById($reasonId);

        if (!$reason) {
            return $this->responder->notFound('messages.contract_rejection_reason_not_found');
        }

        $reasonText = $request->has('reason') ? TranslatableText::fromArray($request->validated('reason')) : $reason->reason;
        
        $reason->update($reasonText);

        if ($request->has('is_active')) {
            $request->validated('is_active') ? $reason->activate() : $reason->deactivate();
        }

        $this->repository->save($reason);

        return $this->responder->success(
            data: ContractRejectionReasonPresenter::fromDomain($reason),
            status: 200,
            messageKey: 'messages.contract_rejection_reason_updated'
        );
    }
}
