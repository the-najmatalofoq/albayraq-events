<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\DeleteAccount\DeleteAccountCommand;
use Modules\User\Application\Command\DeleteAccount\DeleteAccountHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteAccountAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private DeleteAccountHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new DeleteAccountCommand(
            userId: $userId->value
        ));

        $this->tokenManager->invalidateToken();

        return $this->responder->success(
            messageKey: 'user.account_deleted'
        );
    }
}
