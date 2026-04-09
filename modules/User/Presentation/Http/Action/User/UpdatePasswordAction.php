<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\User;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\UpdatePassword\UpdatePasswordCommand;
use Modules\User\Application\Command\UpdatePassword\UpdatePasswordHandler;
use Modules\User\Presentation\Http\Request\UpdatePasswordRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdatePasswordAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdatePasswordHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new UpdatePasswordCommand(
            userId: $userId->value,
            currentPassword: (string) $request->validated('current_password'),
            newPassword: (string) $request->validated('new_password')
        ));

        return $this->responder->success(
            messageKey: __('messages.updated')
        );
    }
}
