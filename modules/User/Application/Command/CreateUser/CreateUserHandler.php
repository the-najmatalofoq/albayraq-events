<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\CreateUser;

use DateTimeImmutable;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;

final readonly class CreateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepository $roleRepository,
        private PasswordHasher $passwordHasher,
        private FileStorageInterface $fileStorage,
    ) {}

    public function handle(CreateUserCommand $command): User
    {
        $role = $this->roleRepository->findById($command->roleId);
        if (!$role) {
            throw new \RuntimeException('Role not found.');
        }

        // Check if user exists by phone
        if ($this->userRepository->findByPhone($command->phone)) {
            throw new \RuntimeException('A user with this phone number already exists.');
        }

        // Check if user exists by email if provided
        if ($command->email && $this->userRepository->findByEmail($command->email)) {
            throw new \RuntimeException('A user with this email already exists.');
        }

        $user = User::register(
            uuid: $this->userRepository->nextIdentity(),
            name: $command->name,
            email: $command->email,
            phone: $command->phone,
            password: $this->passwordHasher->hash($command->password),
            roleIds: [$role->uuid],
            createdAt: new DateTimeImmutable(),
        );

        $filePath = null;
        if ($command->avatar) {
            $filePath = $this->fileStorage->uploadForUser(
                $command->avatar,
                $user->uuid,
                'avatar'
            );
        }

        $this->userRepository->save($user, $filePath?->value);

        return $user;
    }
}
