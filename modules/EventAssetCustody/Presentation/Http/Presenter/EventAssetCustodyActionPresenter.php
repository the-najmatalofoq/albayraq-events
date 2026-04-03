<?php

declare(strict_types=1);

namespace Modules\EventAssetCustody\Presentation\Http\Presenter;

use DateTimeImmutable;
use Modules\User\Domain\ValueObject\UserId;

final class EventAssetCustodyActionPresenter
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
