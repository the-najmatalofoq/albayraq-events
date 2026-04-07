<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\SendEmailVerification\SendEmailVerificationCommand;
use Modules\IAM\Application\Command\SendEmailVerification\SendEmailVerificationHandler;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Presentation\Http\Request\SendEmailVerificationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class SendEmailVerificationAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private SendEmailVerificationHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(SendEmailVerificationRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new SendEmailVerificationCommand(
            userId: $userId->value,
        ));

        return $this->responder->success(
            messageKey: 'auth.verification_sent',
        );
    }
}
