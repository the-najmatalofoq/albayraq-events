<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Header;
use Modules\IAM\Application\Command\RevokeToken\RevokeTokenCommand;
use Modules\IAM\Application\Command\RevokeToken\RevokeTokenHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Group('Authentication')]
final readonly class LogoutAction
{
    public function __construct(
        private RevokeTokenHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    #[Endpoint('Logout the authenticated user', description: 'Revokes all API tokens for the current user.')]
    #[Header('Authorization', description: 'Bearer token', required: true)]
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute('user');

        $this->handler->handle(new RevokeTokenCommand(
            userEmail: $user->email,
        ));

        return $this->responder->success(
            data: null,
            status: 200,
            messageKey: 'messages.auth.logged_out'
        );

    }
}