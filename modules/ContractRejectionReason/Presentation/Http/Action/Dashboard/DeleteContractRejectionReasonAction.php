<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action\Dashboard;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteContractRejectionReasonAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): mixed
    {
        $reasonId = ContractRejectionReasonId::fromString($id);
        $reason = $this->repository->findById($reasonId);

        if (!$reason) {
            return $this->responder->notFound('messages.not_found');
        }

        $this->repository->delete($reasonId);

        return $this->responder->noContent();
    }
}
