<?php
// modules/Role/Infrastructure/Persistence/Seeders/RoleSeeder.php
declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleNameEnum;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Role\Domain\Repository\RoleRepository;

class RoleSeeder extends Seeder
{
    public function __construct(
        private readonly RoleRepository $repository
    ) {}

    public function run(): void
    {
        foreach (RoleNameEnum::cases() as $case) {
            if ($this->repository->findByName($case)) {
                continue;
            }

            $role = Role::create(
                uuid: $this->repository->nextIdentity(),
                name: $case
            );

            $this->repository->save($role);
        }
    }
}
