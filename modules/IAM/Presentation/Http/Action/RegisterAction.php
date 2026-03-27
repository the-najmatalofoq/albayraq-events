<?php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\IAM\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Repository\RoleRepository;
use Modules\IAM\Presentation\Http\Presenter\UserPresenter;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Group('Authentication')]
final readonly class RegisterAction
{
    public function __construct(
        private RegisterUserHandler $handler,
        private UserRepositoryInterface $repository,
        private RoleRepository $roleRepository,
        private RegisterRequest $request,
        private JsonResponder $responder,
    ) {}

    #[Endpoint('Register a new user')]
    #[BodyParameter('name', type: 'string', description: 'User full name', required: true)]
    #[BodyParameter('email', type: 'string', format: 'email', description: 'User email address', required: true)]
    #[BodyParameter('password', type: 'string', description: 'User password (min 8 characters)', required: true)]
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->request->validated($request);

        $userId = $this->handler->handle(new RegisterUserCommand(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
        ));

        $user = $this->repository->findByUuid($userId->value);
        $roleNames = array_map(
            fn($roleId) => $this->roleRepository->findById($roleId)?->name->value ?? 'unknown',
            $user->roleIds
        );

        return $this->responder->success(
            data: UserPresenter::fromDomain($user, $roleNames),
            status: 201,
            messageKey: 'messages.auth.registration_success'
        );

    }
}
