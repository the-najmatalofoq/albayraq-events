<?php
declare(strict_types=1);

namespace Modules\Role\Domain;

use Modules\Role\Domain\Enum\RoleNameEnum;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;

final class Role extends Entity
{
    private function __construct(
        public readonly RoleId $uuid,
        public readonly RoleNameEnum $name,
    ) {
    }

    public static function create(RoleId $uuid, RoleNameEnum $name): self
    {
        return new self(uuid: $uuid, name: $name);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
