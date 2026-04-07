<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\ResetPassword\ResetPasswordCommand;
use Modules\IAM\Application\Command\ResetPassword\ResetPasswordHandler;
use Modules\IAM\Presentation\Http\Request\ResetPasswordRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ResetPasswordAction
{
    public function __construct(
        private ResetPasswordHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $this->handler->handle(new ResetPasswordCommand(
            email: (string) $request->validated('email'),
            code: (string) $request->validated('code'),
            password: (string) $request->validated('password'),
        ));

        return $this->responder->success(
            messageKey: 'auth.password_reset_success',
        );
    }
}
