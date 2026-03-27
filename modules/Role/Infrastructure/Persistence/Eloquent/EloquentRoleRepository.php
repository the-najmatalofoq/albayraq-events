<?php

declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Eloquent;

use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleNameEnum;
use Modules\Role\Domain\ValueObject\RoleId;

final class EloquentRoleRepository implements RoleRepository
{
    public function save(Role $role): void
    {
        RoleModel::query()->updateOrInsert(
            ['uuid' => $role->uuid->value],
            ['name' => $role->name->value],
        );
    }

    public function findById(RoleId $id): ?Role
    {
        $record = RoleModel::where('uuid', $id->value)->first();

        if (! $record) {
            return null;
        }

        return Role::create(
            uuid: RoleId::fromString($record->uuid),
            name: RoleNameEnum::from($record->name),
        );
    }

    public function findByName(RoleNameEnum $name): ?Role
    {
        $record = RoleModel::where('name', $name->value)->first();

        if (! $record) {
            return null;
        }

        return Role::create(
            uuid: RoleId::fromString($record->uuid),
            name: RoleNameEnum::from($record->name),
        );
    }

    public function nextIdentity(): RoleId
    {
        return RoleId::generate();
    }
}
