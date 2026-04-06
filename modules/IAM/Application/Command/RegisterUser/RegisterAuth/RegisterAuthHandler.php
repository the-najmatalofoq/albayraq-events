<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAuth/RegisterAuthHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

use DateTimeImmutable;
use Modules\IAM\Domain\Exception\UserAlreadyExistsException;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;

use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\Shared\Domain\Service\FileStorageInterface;

final readonly class RegisterAuthHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepository $roleRepository,
        private PasswordHasher $passwordHasher,
        private FileStorageInterface $fileStorage,
    ) {}

    public function handle(RegisterAuthCommand $command)
    {
        $role = $this->roleRepository->findBySlug(RoleSlugEnum::EMPLOYEE);

        if (!$role) {
            throw new \RuntimeException('Employee role not found.');
        }
        if ($this->userRepository->findByPhone($command->phone)) {
            throw UserAlreadyExistsException::withPhone($command->phone);
        }

        if ($command->email && $this->userRepository->findByEmail($command->email)) {
            throw UserAlreadyExistsException::withEmail($command->email);
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
        $filePath = $this->fileStorage->uploadForUser(
            $command->avatar,
            $user->uuid,
            'avatar'
        );
        $this->userRepository->save($user, $filePath->value);

        return $user;
    }
}
