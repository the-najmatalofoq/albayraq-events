<?php
// modules/Role/Domain/Enum/RoleLevelEnum.php
declare(strict_types=1);

namespace Modules\Role\Domain\Enum;

enum RoleLevelEnum: string
{
    case SYSTEM     = 'system';
    case EXECUTIVE  = 'executive';
    case PROJECT    = 'project';
    case AREA       = 'area';
    case SITE       = 'site';
    case SUPERVISOR = 'supervisor';
    case INDIVIDUAL = 'individual';

    public function rank(): int
    {
        return match($this) {
            self::SYSTEM     => 0,
            self::EXECUTIVE  => 1,
            self::PROJECT    => 3,
            self::AREA       => 4,
            self::SITE       => 5,
            self::SUPERVISOR => 6,
            self::INDIVIDUAL => 7,
        };
    }

    public function isHigherThan(RoleLevelEnum $other): bool
    {
        return $this->rank() < $other->rank();
    }
}
