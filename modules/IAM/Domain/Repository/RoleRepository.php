<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Repository;

use Modules\IAM\Domain\Role;
use Modules\IAM\Domain\Enum\RoleNameEnum;
use Modules\IAM\Domain\ValueObject\RoleId;

interface RoleRepository
{
    public function save(Role $role): void;

    public function findById(RoleId $id): ?Role;

    public function findByName(RoleNameEnum $name): ?Role;

    public function nextIdentity(): RoleId;
}
