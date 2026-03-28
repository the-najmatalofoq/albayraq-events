<?php
// modules/EventOperationalReport/Domain/Enum/ReportStatusEnum.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Domain\Enum;

enum ReportStatusEnum: string
{
    case DRAFT = 'DRAFT';
    case SUBMITTED = 'SUBMITTED';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}
