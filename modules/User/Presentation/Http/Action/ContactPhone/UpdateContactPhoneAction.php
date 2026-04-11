<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\ContactPhone;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\UpdateContactPhone\UpdateContactPhoneCommand;
use Modules\User\Application\Command\UpdateContactPhone\UpdateContactPhoneHandler;
use Modules\User\Presentation\Http\Request\UpdateContactPhoneRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;

final readonly class UpdateContactPhoneAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateContactPhoneHandler $handler,
        private ContactPhoneRepositoryInterface $contactPhoneRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateContactPhoneRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }
        $contactPhone = $this->contactPhoneRepository->findByUserId($userId);

        $this->handler->handle(new UpdateContactPhoneCommand(
            userId: $userId,
            name: (string) $request->validated('name'),
            phone: (string) $request->validated('phone'),
            relation: (string) $request->validated('relation')
        ));

        return $this->responder->success(
            messageKey: 'messages.updated'
        );
    }
}
