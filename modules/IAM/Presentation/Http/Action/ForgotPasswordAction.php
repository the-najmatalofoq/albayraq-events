<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\ForgotPassword\ForgotPasswordCommand;
use Modules\IAM\Application\Command\ForgotPassword\ForgotPasswordHandler;
use Modules\IAM\Presentation\Http\Request\ForgotPasswordRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ForgotPasswordAction
{
    public function __construct(
        private ForgotPasswordHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $this->handler->handle(new ForgotPasswordCommand(
            email: (string) $request->validated('email'),
        ));

        return $this->responder->success(
            messageKey: 'auth.password_reset_sent',
        );
    }
}
