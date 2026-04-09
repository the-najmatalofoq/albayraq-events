<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Seeders;

use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Modules\User\Domain\User;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\Phone;

class UserSeeder extends Seeder
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly RoleRepository $roleRepository,
        private readonly PasswordHasher $hasher,
    ) {}

    public function run(): void
    {
        $roles = [
            'superadmin' => $this->roleRepository->findBySlug(RoleSlugEnum::SYSTEM_CONTROLLER),
            'admin' => $this->roleRepository->findBySlug(RoleSlugEnum::GENERAL_MANAGER),
            'manager' => $this->roleRepository->findBySlug(RoleSlugEnum::OPERATIONS_MANAGER),
            'supervisor' => $this->roleRepository->findBySlug(RoleSlugEnum::SUPERVISOR),
            'employee' => $this->roleRepository->findBySlug(RoleSlugEnum::INDIVIDUAL),
        ];

        $users = [
            ['Super Admin', 'superadmin@events.com', '0500000001', 'superadmin'],
            ['Admin User', 'admin@events.com', '0500000002', 'admin'],
            ['Operations Manager', 'ops@events.com', '0500000003', 'admin'],
            ['Project Manager', 'pm@events.com', '0500000004', 'manager'],
            ['Event Manager', 'event.manager@events.com', '0500000005', 'manager'],
            ['Area Supervisor', 'supervisor@events.com', '0500000006', 'supervisor'],
            ['Site Supervisor', 'site.supervisor@events.com', '0500000007', 'supervisor'],
            ['John Doe', 'john@example.com', '0500000008', 'employee'],
            ['Jane Smith', 'jane@example.com', '0500000009', 'employee'],
            ['Mike Johnson', 'mike@example.com', '0500000010', 'employee'],
        ];

        foreach ($users as [$name, $email, $phone, $roleKey]) {
            if ($this->repository->findByEmail($email)) {
                continue;
            }

            $role = $roles[$roleKey];
            if (!$role) {
                $this->command->error("Role not found for: {$roleKey}");
                continue;
            }

            $user = User::register(
                uuid: $this->repository->nextIdentity(),
                name: TranslatableText::fromArray(['en' => $name, 'ar' => $name]),
                email: $email,
                phone: new Phone($phone),
                password: $this->hasher->hash('password'),
                roleIds: [$role->uuid],
                createdAt: new DateTimeImmutable(),
            );
            $this->repository->save($user);
            }
        }
    }
