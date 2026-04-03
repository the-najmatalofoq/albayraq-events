<?php
// modules/User/Domain/Enum/GenderEnum.php
declare(strict_types=1);

namespace Modules\User\Domain\Enum;

enum GenderEnum: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
