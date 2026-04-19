<?php
// modules/EventBreakRequest/Domain/BreakRequestStatus.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Domain;

enum BreakRequestStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
}
