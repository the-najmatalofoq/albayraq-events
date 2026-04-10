<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\ContactPhone;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\DeleteContactPhone\DeleteContactPhoneCommand;
use Modules\User\Application\Command\DeleteContactPhone\DeleteContactPhoneHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;

final readonly class DeleteContactPhoneAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private DeleteContactPhoneHandler $handler,
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $contactPhone = $this->contactPhoneRepository->findByUserId($userId);

        if ($contactPhone === null) {
            return $this->responder->notFound();
        }

        $this->handler->handle(new DeleteContactPhoneCommand(
            userId: $userId,
            contactPhoneId: $contactPhone->uuid
        ));

        return $this->responder->noContent();
    }
}
