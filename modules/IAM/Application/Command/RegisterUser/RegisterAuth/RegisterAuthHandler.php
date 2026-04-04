<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAuth/RegisterAuthHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

use DateTimeImmutable;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\IAM\Domain\Service\PasswordHasher;

final readonly class RegisterAuthHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepository $roleRepository,
        private PasswordHasher $passwordHasher,
    ) {
    }

    public function handle(RegisterAuthCommand $command): void
    {
        $userId = new UserId($command->userId);
        
        $role = $this->roleRepository->findBySlug(RoleSlugEnum::EMPLOYEE);

        if (!$role) {
            throw new \RuntimeException('Employee role not found.');
        }

        $name = is_array($command->name) 
            ? $command->name 
            : ['ar' => $command->name, 'en' => $command->name];

        $user = User::register(
            uuid: $userId,
            name: TranslatableText::fromArray($name),
            email: $command->email,
            phone: new Phone($command->phone),
            password: $this->passwordHasher->hash($command->password),
            roleIds: [$role->uuid],
            createdAt: new DateTimeImmutable(),
            nationalId: $command->nationalId,
            // fix: we intrude the join request instead of isActive right?
            isActive: false
        );

        $this->userRepository->save($user);
    }
}