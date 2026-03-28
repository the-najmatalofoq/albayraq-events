<?php
// modules/Role/Domain/Enum/RoleSlugEnum.php
declare(strict_types=1);

namespace Modules\Role\Domain\Enum;

enum RoleSlugEnum: string
{
    case SYSTEM_CONTROLLER  = 'system_controller';
    case GENERAL_MANAGER    = 'general_manager';
    case OPERATIONS_MANAGER = 'operations_manager';
    case PROJECT_MANAGER    = 'project_manager';
    case AREA_MANAGER       = 'area_manager';
    case SITE_MANAGER       = 'site_manager';
    case SUPERVISOR         = 'supervisor';
    case INDIVIDUAL         = 'individual';
    case ADMISSIONS_ADMIN   = 'admissions_admin';

    public function level(): RoleLevelEnum
    {
        return match($this) {
            self::SYSTEM_CONTROLLER  => RoleLevelEnum::SYSTEM,
            self::GENERAL_MANAGER,
            self::OPERATIONS_MANAGER => RoleLevelEnum::EXECUTIVE,
            self::PROJECT_MANAGER    => RoleLevelEnum::PROJECT,
            self::AREA_MANAGER,
            self::ADMISSIONS_ADMIN   => RoleLevelEnum::AREA,
            self::SITE_MANAGER       => RoleLevelEnum::SITE,
            self::SUPERVISOR         => RoleLevelEnum::SUPERVISOR,
            self::INDIVIDUAL         => RoleLevelEnum::INDIVIDUAL,
        };
    }

    public function isGlobal(): bool
    {
        return match($this) {
            self::SYSTEM_CONTROLLER,
            self::GENERAL_MANAGER,
            self::OPERATIONS_MANAGER => true,
            default => false,
        };
    }
}
