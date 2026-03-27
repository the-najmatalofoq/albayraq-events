<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use DateTimeImmutable;
use Modules\IAM\Domain\Exception\UserAlreadyExistsException;
use Modules\IAM\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Repository\RoleRepository;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\User;
use Modules\IAM\Domain\Enum\RoleNameEnum;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Application\EventDispatcher;
use Modules\Shared\Application\EventDispatchingHandler;

final readonly class RegisterUserHandler extends EventDispatchingHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private RoleRepository $roleRepository,
        private PasswordHasher $hasher,
        EventDispatcher $dispatcher,
    ) {
        parent::__construct($dispatcher);
    }

    public function handle(RegisterUserCommand $command): UserId
    {
        if ($this->repository->findByPhone($command->phone)) {
            throw UserAlreadyExistsException::withPhone($command->phone);
        }

        $defaultRole = $this->roleRepository->findByName(RoleNameEnum::INDIVIDUAL);
        if (!$defaultRole) {
            throw new \RuntimeException('Default role not found');
        }

        $userId = $this->repository->nextIdentity();
        $user = User::register(
            uuid: $userId,
            name: $command->name,
            email: $command->email,
            phone: $command->phone,
            password: $this->hasher->hash($command->password),
            roleIds: [$defaultRole->uuid],
            createdAt: new DateTimeImmutable,
        );

        $this->repository->save($user);
        $this->dispatchEvents($user);

        return $userId;
    }
}
