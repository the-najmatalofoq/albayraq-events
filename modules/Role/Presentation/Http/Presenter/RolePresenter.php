<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Presenter;

use Modules\Role\Domain\Role;

final class RolePresenter
{
    public static function fromDomain(Role $role): array
    {
        return [
            'id' => $role->uuid->value,
            'slug' => $role->slug->value,
            'name' => $role->name->toArray(),
            'is_global' => $role->isGlobal,
            'level' => $role->level->value,
            'level_name' => strtolower($role->level->name),
        ];
    }
}
