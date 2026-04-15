<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\User;

use Exception;
use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestCommand;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestHandler;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Presentation\Http\Request\UpdateMeRequest;

final readonly class UpdateMeAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private SubmitUpdateRequestHandler $handler,
        private UserRepositoryInterface $userRepository,
        private UserUpdateRequestRepositoryInterface $updateRequestRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateMeRequest $request): JsonResponse
    {   
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        // Check for an existing pending request for user_info
        $existingRequests = $this->updateRequestRepository->findByUserId($userId->value);
        $hasPending = !empty(array_filter(
            $existingRequests,
            fn($r) =>
            $r->status === UpdateRequestStatus::PENDING &&
                $r->targetType === 'user_info'
        ));

        if ($hasPending) {
            throw \Modules\User\Domain\Exception\PendingUpdateRequestException::forTarget('messages.targets.user_info');
        }

        // Ensure the user exists
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new Exception("المستخدم غير موجود");
        }

        $command = new SubmitUpdateRequestCommand(
            userId: $userId,
            targetType: 'user_info',
            targetId: $userId->value,
            newData: $request->validated(),
        );

        $updateRequest = $this->handler->handle($command);

        return $this->responder->success(
            data: [
                'request_id' => $updateRequest->id()->value,
                'status'     => $updateRequest->status->value,
            ],
            messageKey: 'messages.user_update_requests.submitted',
        );
    }
}
