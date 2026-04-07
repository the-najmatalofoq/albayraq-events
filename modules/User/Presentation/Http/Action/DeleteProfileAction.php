<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\DeleteUserProfile\DeleteUserProfileCommand;
use Modules\User\Application\Command\DeleteUserProfile\DeleteUserProfileHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteProfileAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private DeleteUserProfileHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new DeleteUserProfileCommand(
            userId: $userId->value
        ));

        return $this->responder->success(
            messageKey: 'user.profile_deleted'
        );
    }
}
