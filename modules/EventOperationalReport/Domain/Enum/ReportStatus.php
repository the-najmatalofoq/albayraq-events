<?php
// modules/EventOperationalReport/Domain/Enum/ReportStatus.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Domain\Enum;

enum ReportStatus: string
{
    case DRAFT = 'DRAFT';
    case SUBMITTED = 'SUBMITTED';
    case APPROVED = 'APPROVED';
}
