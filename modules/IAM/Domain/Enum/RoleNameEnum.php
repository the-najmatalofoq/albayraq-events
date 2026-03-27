<?php
// modules/IAM/Domain/Enum/RoleNameEnum.php

declare(strict_types=1);

namespace Modules\IAM\Domain\Enum;

enum RoleNameEnum: string
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
}
