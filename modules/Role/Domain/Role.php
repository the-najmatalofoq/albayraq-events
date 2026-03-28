<?php

declare(strict_types=1);

namespace Modules\Role\Domain;

use Modules\Role\Domain\Enum\RoleLevelEnum;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final class Role extends Entity
{
    private function __construct(
        public readonly RoleId $uuid,
        public readonly RoleSlugEnum $slug,
        public readonly TranslatableText $name,
        public readonly bool $isGlobal,
        public readonly RoleLevelEnum $level,
    ) {
    }

    public static function create(
        RoleId $uuid,
        RoleSlugEnum $slug,
        TranslatableText $name,
        bool $isGlobal,
        RoleLevelEnum $level
    ): self {
        return new self($uuid, $slug, $name, $isGlobal, $level);
    }

    public static function fromSlug(
        RoleId $uuid,
        RoleSlugEnum $slug,
        TranslatableText $name,
    ): self {
        return new self(
            uuid: $uuid,
            slug: $slug,
            name: $name,
            isGlobal: $slug->isGlobal(),
            level: $slug->level(),
        );
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
