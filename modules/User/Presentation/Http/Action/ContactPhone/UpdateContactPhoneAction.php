<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\ContactPhone;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestCommand;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestHandler;
use Modules\User\Presentation\Http\Request\UpdateContactPhoneRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\Exception\PendingUpdateRequestException;

final readonly class UpdateContactPhoneAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private SubmitUpdateRequestHandler $handler,
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
        private UserUpdateRequestRepositoryInterface $updateRequestRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateContactPhoneRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        // Check for an existing pending request for contact_phone
        $existingRequests = $this->updateRequestRepository->findByUserId($userId->value);
        $hasPending = !empty(array_filter(
            $existingRequests,
            fn($r) =>
            $r->status === UpdateRequestStatus::PENDING &&
                $r->targetType === 'contact_phone'
        ));

        if ($hasPending) {
            throw PendingUpdateRequestException::forTarget('messages.targets.contact_phone');
        }

        $contactPhone = $this->contactPhoneRepository->findByUserId($userId);

        if (!$contactPhone) {
            throw new \Exception(__('messages.not_found'));
        }

        $command = new SubmitUpdateRequestCommand(
            userId: $userId,
            targetType: 'contact_phone',
            targetId: $contactPhone->uuid->value,
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
