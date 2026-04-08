<?php
// modules/IAM/Presentation/Http/Action/LoginAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\AuthenticateUser\AuthenticateUserCommand;
use Modules\IAM\Application\Command\AuthenticateUser\AuthenticateUserHandler;
use Modules\IAM\Presentation\Http\Presenter\AuthenticationPresenter;
use Modules\IAM\Presentation\Http\Request\LoginRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;

final class LoginAction
{
    public function __construct(
        private readonly AuthenticateUserHandler $handler,
        private readonly JsonResponder $responder,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserJoinRequestRepositoryInterface $userJoinRequestRepository,
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $command = new AuthenticateUserCommand(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );

        $result = $this->handler->handle($command);

        return $this->responder->success(
            data: AuthenticationPresenter::fromResult($result),
            messageKey: __('messages.auth.logged_in')
        );
    }
}
