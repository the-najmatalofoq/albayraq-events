<?php
// modules/ParticipationViolation/Domain/Enum/ViolationStatusEnum.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Domain\Enum;

enum ViolationStatusEnum: string
{
    case PENDING = 'pending';
    case ESCALATED = 'escalated';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
