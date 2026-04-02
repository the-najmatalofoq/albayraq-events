<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAuth/RegisterAuthHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

use DateTimeImmutable;
use Modules\IAM\Domain\Exception\UserAlreadyExistsException;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\FilePath;
use Modules\User\Domain\ValueObject\Phone;

final readonly class RegisterAuthHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepository $roleRepository,
        private PasswordHasher $hasher,
    ) {}

    public function handle(RegisterAuthCommand $command): UserId
    {
        if ($this->userRepository->findByPhone($command->phone)) {
            throw UserAlreadyExistsException::withPhone($command->phone);
        }

        if ($command->email && $this->userRepository->findByEmail($command->email)) {
            throw UserAlreadyExistsException::withEmail($command->email);
        }

        $userId = $this->userRepository->nextIdentity();
        $defaultRole = $this->roleRepository->findBySlug(RoleSlugEnum::INDIVIDUAL);
        if (!$defaultRole) {
            throw new \RuntimeException('Default role (individual) not found');
        }

        $user = User::register(
            uuid: $userId,
            name: $command->name,
            email: $command->email,
            phone: new Phone($command->phone),
            avatar: $command->avatar ? new FilePath($command->avatar) : null,
            password: $this->hasher->hash($command->password),
            roleIds: [$defaultRole->uuid],
            createdAt: new DateTimeImmutable(),
        );

        $this->userRepository->save($user);

        return $userId;
    }
}