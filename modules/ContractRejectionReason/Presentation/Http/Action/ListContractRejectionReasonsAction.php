<?php
// modules/ContractRejectionReason/Presentation/Http/Action/ListContractRejectionReasonsAction.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Action;

use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Presentation\Http\Presenter\ContractRejectionReasonPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListContractRejectionReasonsAction
{
    public function __construct(
        private ContractRejectionReasonRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(): mixed
    {
        $reasons = $this->repository->listAll();
        
        return $this->responder->success(
            data: array_map(fn($reason) => ContractRejectionReasonPresenter::fromDomain($reason), $reasons)
        );
    }
}
