<?php
// modules/EventExpense/Domain/Enum/ExpenseStatusEnum.php
declare(strict_types=1);

namespace Modules\EventExpense\Domain\Enum;

enum ExpenseStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PAID = 'paid';
}
