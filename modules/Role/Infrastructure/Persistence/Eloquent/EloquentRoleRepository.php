<?php

declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Eloquent;

use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final class EloquentRoleRepository implements RoleRepository
{
    public function save(Role $role): void
    {
        RoleModel::query()->updateOrCreate(
            ['id' => $role->uuid->value],
            [
                'slug' => $role->slug->value,
                'name' => $role->name->toArray(),
                'is_global' => $role->isGlobal,
                'level' => $role->level->value,
            ]
        );
    }

    public function findById(RoleId $id): ?Role
    {
        $record = RoleModel::find($id->value);

        return $record ? $this->toDomain($record) : null;
    }

    public function findBySlug(RoleSlugEnum $slug): ?Role
    {
       
     $record = RoleModel::where('slug', $slug->value)->first();

        return $record ? $this->toDomain($record) : null;
    }

    public function nextIdentity(): RoleId
    {
        return RoleId::generate();
    }

    private function toDomain(RoleModel $record): Role
    {
        return Role::create(
            uuid: RoleId::fromString($record->id),
            slug: RoleSlugEnum::from($record->slug),
            name: TranslatableText::fromArray($record->name),
            isGlobal: $record->is_global,
            level: $record->level,
        );
    }
}
