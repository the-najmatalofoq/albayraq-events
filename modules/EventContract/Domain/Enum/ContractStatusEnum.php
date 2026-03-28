<?php
// modules/EventContract/Domain/Enum/ContractStatusEnum.php
declare(strict_types=1);

namespace Modules\EventContract\Domain\Enum;

enum ContractStatusEnum: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
}
