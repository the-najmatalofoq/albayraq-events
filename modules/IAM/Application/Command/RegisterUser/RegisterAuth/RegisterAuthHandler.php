<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAuth/RegisterAuthHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

use DateTimeImmutable;
use Modules\Role\Domain\ValueObject\RoleName;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\HashedPassword;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

// fix: we must make the RoleRepositoryInterface
final readonly class RegisterAuthHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
    ) {
    }

    public function handle(RegisterAuthCommand $command): void
    {
        $userId = new UserId($command->userId);
        // fix the RoleName
        $role = $this->roleRepository->findByName(new RoleName('employee'));

        if (!$role) {
            throw new \RuntimeException('Employee role not found.');
        }

        $user = User::register(
            uuid: $userId,
            name: TranslatableText::fromArray($command->name),
            email: $command->email,
            phone: new Phone($command->phone),
            // fix the fromRaw
            password: HashedPassword::fromRaw($command->password),
            roleIds: [$role->uuid],
            createdAt: new DateTimeImmutable(),
            nationalId: $command->nationalId,
            isActive: true
        );

        $this->userRepository->save($user);
    }
}