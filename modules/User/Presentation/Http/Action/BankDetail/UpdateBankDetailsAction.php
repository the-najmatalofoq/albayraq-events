<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\BankDetail;

use Exception;
use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestCommand;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestHandler;
use Modules\User\Presentation\Http\Request\UpdateBankDetailsRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\Exception\PendingUpdateRequestException;

final readonly class UpdateBankDetailsAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private SubmitUpdateRequestHandler $handler,
        private BankDetailRepositoryInterface $bankAccountRepository,
        private UserUpdateRequestRepositoryInterface $updateRequestRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateBankDetailsRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $existingRequests = $this->updateRequestRepository->findByUserId($userId->value);
        $hasPending = !empty(array_filter(
            $existingRequests,
            fn($r) =>
            $r->status === UpdateRequestStatus::PENDING &&
                $r->targetType === 'bank_account'
        ));

        if ($hasPending) {
            throw PendingUpdateRequestException::forTarget('messages.targets.bank_account');
        }

        $bankAccount = $this->bankAccountRepository->findByUserId($userId);

        if (!$bankAccount) {
            throw new Exception(__('messages.not_found'));
        }

        $command = new SubmitUpdateRequestCommand(
            userId: $userId,
            targetType: 'bank_account',
            targetId: $bankAccount->id()->value,
            newData: $request->validated()
        );

        $updateRequest = $this->handler->handle($command);

        return $this->responder->success(
            data: [
                'request_id' => $updateRequest->id()->value,
                'status' => $updateRequest->status->value,
            ],
            messageKey: 'messages.bank_details.update_request_submitted'
        );
    }
}
