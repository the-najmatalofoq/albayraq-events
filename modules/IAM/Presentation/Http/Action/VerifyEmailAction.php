<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\VerifyEmail\VerifyEmailCommand;
use Modules\IAM\Application\Command\VerifyEmail\VerifyEmailHandler;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Presentation\Http\Request\VerifyEmailRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class VerifyEmailAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private VerifyEmailHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(VerifyEmailRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new VerifyEmailCommand(
            userId: $userId->value,
            code: (string) $request->validated('code'),
        ));

        return $this->responder->success(
            messageKey: 'auth.email_verified',
        );
    }
}
