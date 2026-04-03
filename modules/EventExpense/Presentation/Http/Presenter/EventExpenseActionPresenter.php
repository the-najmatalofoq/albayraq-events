<?php

declare(strict_types=1);

namespace Modules\EventExpense\Presentation\Http\Presenter;

use DateTimeImmutable;
use Modules\User\Domain\ValueObject\UserId;

final class EventExpenseActionPresenter
{
    public static function present(?DateTimeImmutable $at, ?UserId $by): ?array
    {
        if ($at === null || $by === null) {
            return null;
        }

        return [
            'at' => $at->format(DATE_ATOM),
            'by' => $by->value,
        ];
    }
}
