<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\BankDetail;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\BankDetailPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetBankDetailsAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private BankDetailRepositoryInterface $bankDetailRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $bankDetail = $this->bankDetailRepository->findByUserId($userId);

        return $this->responder->success(
            data: BankDetailPresenter::fromDomain($bankDetail)
        );
    }
}
