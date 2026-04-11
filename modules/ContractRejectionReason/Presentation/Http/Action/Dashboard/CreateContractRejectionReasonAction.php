<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Presentation\Http\Presenter\ContractRejectionReasonPresenter;
use Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard\StoreContractRejectionReasonRequest;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateContractRejectionReasonAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(StoreContractRejectionReasonRequest $request): mixed
    {
        $id = $this->repository->nextIdentity();

        $reason = ContractRejectionReason::create(
            uuid: $id,
            reason: TranslatableText::fromMixed($request->validated('reason')),
            isActive: (bool)$request->validated('is_active', true)
        );

        $this->repository->save($reason);

        return $this->responder->created(
            data: ['id' => $reason->uuid->value],
            messageKey: 'messages.created'
        );
    }
}
