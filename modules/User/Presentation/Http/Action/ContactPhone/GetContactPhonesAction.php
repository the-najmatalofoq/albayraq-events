<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\ContactPhone;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\ContactPhonePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetContactPhonesAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $contactPhones = $this->contactPhoneRepository->findByUserId($userId);

        return $this->responder->success(
            data: ContactPhonePresenter::collection($contactPhones)
        );
    }
}
