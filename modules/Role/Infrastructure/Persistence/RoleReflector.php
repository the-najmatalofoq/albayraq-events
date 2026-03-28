<?php
// modules/Role/Infrastructure/Persistence/RoleReflector.php
declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence;

use Modules\Role\Domain\Role;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Role\Domain\Enum\RoleLevelEnum;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;

final class RoleReflector
{
    public static function fromModel(RoleModel $model): Role
    {
        $reflection = new \ReflectionClass(Role::class);
        $role = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'      => RoleId::fromString($model->id),
            'slug'      => RoleSlugEnum::from($model->slug),
            'name'      => TranslatableText::fromArray($model->name),
            'isGlobal'  => (bool) $model->is_global,
            'level'     => RoleLevelEnum::from($model->level),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($role, $value);
        }

        return $role;
    }
}
