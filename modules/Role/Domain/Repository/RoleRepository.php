<?php

declare(strict_types=1);

namespace Modules\Role\Domain\Repository;

use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleNameEnum;
use Modules\Role\Domain\ValueObject\RoleId;

interface RoleRepository
{
    public function save(Role $role): void;

    public function findById(RoleId $id): ?Role;

    public function findByName(RoleNameEnum $name): ?Role;

    public function nextIdentity(): RoleId;
}
