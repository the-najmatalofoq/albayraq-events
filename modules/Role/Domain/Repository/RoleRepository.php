<?php

declare(strict_types=1);

namespace Modules\Role\Domain\Repository;

use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface RoleRepository extends FilterableRepositoryInterface
{
    public function save(Role $role): void;

    public function findById(RoleId $id): ?Role;

    public function findBySlug(RoleSlugEnum $slug): ?Role;

    public function nextIdentity(): RoleId;

    public function delete(RoleId $id): void;
}
