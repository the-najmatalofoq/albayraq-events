<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\User;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\UpdateEmail\UpdateEmailCommand;
use Modules\User\Application\Command\UpdateEmail\UpdateEmailHandler;
use Modules\User\Presentation\Http\Request\UpdateEmailRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateEmailAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateEmailHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateEmailRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new UpdateEmailCommand(
            userId: $userId->value,
            email: (string) $request->validated('email')
        ));

        return $this->responder->success(
            messageKey: __('messages.updated')
        );
    }
}
