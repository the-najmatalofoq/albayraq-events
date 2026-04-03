<?php
// modules/User/Presentation/Http/Action/DeleteContactPhoneAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Application\Command\DeleteContactPhone\DeleteContactPhoneCommand;
use Modules\User\Application\Command\DeleteContactPhone\DeleteContactPhoneHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteContactPhoneAction
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
        private DeleteContactPhoneHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if (!$userId) {
            return $this->responder->unauthorized();
        }

        $command = new DeleteContactPhoneCommand(
            userId: $userId->value,
            phoneId: $id,
        );

        $this->handler->handle($command);

        return $this->responder->success(
            messageKey: 'profile.phone_deleted'
        );
    }
}
