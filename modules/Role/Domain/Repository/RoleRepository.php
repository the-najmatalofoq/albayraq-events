<?php

declare(strict_types=1);

namespace Modules\Role\Domain\Repository;

use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\ValueObject\RoleId;

interface RoleRepository
{
    public function save(Role $role): void;

    public function findById(RoleId $id): ?Role;

    public function findBySlug(RoleSlugEnum $slug): ?Role;

    public function nextIdentity(): RoleId;
}
