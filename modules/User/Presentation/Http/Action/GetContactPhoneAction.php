<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Presentation\Http\Presenter\ContactPhonePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetContactPhoneAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $contactPhoneId = ContactPhoneId::fromString($id);
        $contactPhone = $this->contactPhoneRepository->findById($contactPhoneId);

        if ($contactPhone === null || $contactPhone->userId->value !== $userId->value) {
            return $this->responder->notFound();
        }

        return $this->responder->success(
            data: ContactPhonePresenter::fromDomain($contactPhone)
        );
    }
}
