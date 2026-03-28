<?php
// modules/IAM/Presentation/Http/Action/RegisterAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\Group;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Group('Auth')]
final readonly class RegisterAction
{
    public function __construct(
        private RegisterUserHandler $handler,
        private UserRepositoryInterface $repository,
        private RoleRepository $roleRepository,
        private UserPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(RegisterRequest $request): ResponseInterface
    {
        $command = new RegisterUserCommand(
            name: TranslatableText::fromArray($request->validated('name')),
            phone: $request->validated('phone'),
            password: $request->validated('password'),
            email: $request->validated('email'),
        );

        $userId = $this->handler->handle($command);

        $user = $this->repository->findById($userId);
        if (!$user) {
            throw new \RuntimeException('User registered but not found');
        }

        return $this->responder->success(
            data: $this->presenter->present($user),
            messageKey: 'messages.auth.registered'
        );
    }
}
