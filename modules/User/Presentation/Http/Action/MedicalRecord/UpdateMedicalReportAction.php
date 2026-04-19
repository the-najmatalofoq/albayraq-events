<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\MedicalRecord;

use Exception;
use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestCommand;
use Modules\User\Application\Command\SubmitUpdateRequest\SubmitUpdateRequestHandler;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\Repository\MedicalRecordRepositoryInterface;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Presentation\Http\Request\UpdateMedicalReportRequest;

final readonly class UpdateMedicalReportAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private SubmitUpdateRequestHandler $handler,
        private MedicalRecordRepositoryInterface $medicalRecordRepository,
        private UserUpdateRequestRepositoryInterface $updateRequestRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateMedicalReportRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        // Check for an existing pending request for medical_record
        $existingRequests = $this->updateRequestRepository->findByUserId($userId->value);
        $hasPending = !empty(array_filter($existingRequests, fn($r) =>
            $r->status === UpdateRequestStatus::PENDING &&
            $r->targetType === 'medical_record'
        ));

        if ($hasPending) {
            throw \Modules\User\Domain\Exception\PendingUpdateRequestException::forTarget('messages.targets.medical_record');
        }

        // Retrieve the medical record to link the target id
        $medicalRecord = $this->medicalRecordRepository->findByUserId($userId);

        if (!$medicalRecord) {
            throw new Exception("لا يوجد سجل طبي لهذا المستخدم");
        }

        $command = new SubmitUpdateRequestCommand(
            userId: $userId,
            targetType: 'medical_record',
            targetId: $medicalRecord->id()->value,
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
