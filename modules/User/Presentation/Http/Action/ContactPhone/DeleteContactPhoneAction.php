<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\ContactPhone;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\DeleteContactPhone\DeleteContactPhoneCommand;
use Modules\User\Application\Command\DeleteContactPhone\DeleteContactPhoneHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteContactPhoneAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private DeleteContactPhoneHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new DeleteContactPhoneCommand(
            userId: $userId->value,
            contactPhoneId: $id
        ));

        return $this->responder->success(
            messageKey: 'user.contact_phone_deleted'
        );
    }
}
