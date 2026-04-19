<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\EmployeeProfile;

use Exception;
use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestCommand;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestHandler;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Presentation\Http\Request\UpdateProfileRequest;

final readonly class UpdateProfileAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private SubmitUpdateRequestHandler $handler,
        private EmployeeProfileRepositoryInterface $profileRepository,
        private UserUpdateRequestRepositoryInterface $updateRequestRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if (!$userId) {
            return $this->responder->unauthorized();
        }

        // Check for an existing pending request for employee_profile
        $existingRequests = $this->updateRequestRepository->findByUserId($userId->value);
        $hasPending = !empty(array_filter($existingRequests, fn($r) =>
            $r->status === UpdateRequestStatus::PENDING &&
            $r->targetType === 'employee_profile'
        ));

        if ($hasPending) {
            throw \Modules\User\Domain\Exception\PendingUpdateRequestException::forTarget('messages.targets.employee_profile');
        }

        // Retrieve the current profile to link the target id
        $profile = $this->profileRepository->findByUserId($userId);

        if (!$profile) {
            throw new Exception("لا يوجد ملف شخصي لهذا المستخدم");
        }

        $command = new SubmitUpdateRequestCommand(
            userId: $userId,
            targetType: 'employee_profile',
            targetId: $profile->id()->value,
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
