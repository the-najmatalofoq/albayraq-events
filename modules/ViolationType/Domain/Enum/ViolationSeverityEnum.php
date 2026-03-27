<?php
// modules/ViolationType/Domain/Enum/ViolationSeverityEnum.php
declare(strict_types=1);

namespace Modules\ViolationType\Domain\Enum;

enum ViolationSeverityEnum: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
