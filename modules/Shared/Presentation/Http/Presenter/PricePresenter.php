<?php
// modules/Shared/Presentation/Http/Presenter/PricePresenter.php
declare(strict_types=1);

namespace Modules\Shared\Presentation\Http\Presenter;

use Modules\Shared\Domain\ValueObject\Money;

final class PricePresenter
{
    public static function fromDomain(Money $price): array
    {
        return [
            'amount' => $price->amount,
            'currency' => $price->currency,
            'formatted' => number_format($price->amount / 100, 2) . ' ' . $price->currency,
        ];
    }
}
