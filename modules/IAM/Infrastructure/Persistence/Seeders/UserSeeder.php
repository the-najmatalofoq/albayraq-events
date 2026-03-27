<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence\Seeders;

use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Modules\IAM\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Repository\RoleRepository;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\User;
use Modules\IAM\Domain\Enum\RoleNameEnum;

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
            'superadmin' => $this->roleRepository->findByName(RoleNameEnum::SUPERADMIN),
            'admin' => $this->roleRepository->findByName(RoleNameEnum::ADMIN),
            'manager' => $this->roleRepository->findByName(RoleNameEnum::MANAGER),
            'supervisor' => $this->roleRepository->findByName(RoleNameEnum::SUPERVISOR),
            'employee' => $this->roleRepository->findByName(RoleNameEnum::EMPLOYEE),
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
                name: $name,
                email: $email,
                password: $this->hasher->hash('password123'),
                roleIds: $roleIds,
                createdAt: new DateTimeImmutable,
            );
            $this->repository->save($user);
        }
    }
}
