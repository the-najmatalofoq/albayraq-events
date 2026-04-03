<?php
// modules/IAM/Presentation/Http/Action/LoginAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\AuthenticateUser\AuthenticateUserCommand;
use Modules\IAM\Presentation\Http\Request\LoginRequest;
use Modules\Shared\Application\Command\CommandBusInterface;
use Modules\Shared\Presentation\Http\JsonResponder;

// fix: check the CommandBusInterface and the return type.
final class LoginAction
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $command = new AuthenticateUserCommand(
            login: $request->validated('login'),
            password: $request->validated('password')
        );

        $tokenData = $this->commandBus->dispatch($command);

        return $this->responder->success(
            data: $tokenData,
            messageKey: 'auth.logged_in'
        );
    }
}
