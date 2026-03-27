<?php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Seeders;

use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\User\Domain\User;
use Modules\Role\Domain\Enum\RoleNameEnum;

class UserSeeder extends Seeder
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly RoleRepository $roleRepository,
        private readonly PasswordHasher $hasher,
    ) {
    }

    public function run(): void
    {
        $roles = [
            'superadmin' => $this->roleRepository->findByName(RoleNameEnum::SYSTEM_CONTROLLER), // Map to available enum
            'admin' => $this->roleRepository->findByName(RoleNameEnum::GENERAL_MANAGER),
            'manager' => $this->roleRepository->findByName(RoleNameEnum::OPERATIONS_MANAGER),
            'supervisor' => $this->roleRepository->findByName(RoleNameEnum::SUPERVISOR),
            'employee' => $this->roleRepository->findByName(RoleNameEnum::INDIVIDUAL),
        ];

        $users = [
            ['Super Admin', 'superadmin@events.com', [$roles['superadmin']->uuid]],
            ['Admin User', 'admin@events.com', [$roles['admin']->uuid]],
            ['Operations Manager', 'ops@events.com', [$roles['admin']->uuid]],
            ['Project Manager', 'pm@events.com', [$roles['manager']->uuid]],
            ['Event Manager', 'event.manager@events.com', [$roles['manager']->uuid]],
            ['Area Supervisor', 'supervisor@events.com', [$roles['supervisor']->uuid]],
            ['Site Supervisor', 'site.supervisor@events.com', [$roles['supervisor']->uuid]],
            ['John Doe', 'john@example.com', [$roles['employee']->uuid]],
            ['Jane Smith', 'jane@example.com', [$roles['employee']->uuid]],
            ['Mike Johnson', 'mike@example.com', [$roles['employee']->uuid]],
        ];

        foreach ($users as [$name, $email, $roleIds]) {
            if ($this->repository->findByEmail($email))
                continue;

            $user = User::register(
                uuid: $this->repository->nextIdentity(),
                name: ['en' => $name, 'ar' => $name], // Use array for translatable name
                email: $email,
                phone: '123456789' . rand(10, 99), // Dummy phone
                password: $this->hasher->hash('password111'),
                roleIds: $roleIds,
                createdAt: new DateTimeImmutable,
                isActive: true
            );
            $this->repository->save($user);
        }
    }
}
