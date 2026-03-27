<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Modules\IAM\Application\Command\Authenticate\AuthenticateUserCommand;
use Modules\IAM\Application\Command\Authenticate\AuthenticateUserHandler;
use Modules\IAM\Presentation\Http\Request\LoginRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Group('Authentication')]
final readonly class LoginAction
{
    public function __construct(
        private AuthenticateUserHandler $handler,
        private LoginRequest $request,
        private JsonResponder $responder,
    ) {}

    #[Endpoint('Authenticate user and return token')]
    #[BodyParameter('email', type: 'string', format: 'email', description: 'User email', required: true)]
    #[BodyParameter('password', type: 'string', description: 'User password', required: true)]
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->request->validated($request);

        $result = $this->handler->handle(new AuthenticateUserCommand(
            email: $data['email'],
            password: $data['password'],
        ));

        return $this->responder->success(
            data: [
                'token' => $result['token'],
                'user_id' => $result['user_id'],
            ],
            status: 200,
            messageKey: 'messages.auth.login_success'
        );

    }
}
