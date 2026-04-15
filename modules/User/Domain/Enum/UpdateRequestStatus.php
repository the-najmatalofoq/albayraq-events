<?php

declare(strict_types=1);

namespace Modules\User\Domain\Enum;

enum UpdateRequestStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
