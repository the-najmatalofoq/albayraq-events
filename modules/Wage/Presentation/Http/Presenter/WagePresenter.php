<?php
// modules/Wage/Presentation/Http/Presenter/WagePresenter.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Presenter;

use Modules\Wage\Domain\Wage;

final class WagePresenter
{
    public static function fromDomain(Wage $wage): array
    {
        return [
            'id' => $wage->uuid->value,
            'wageable_id' => $wage->wageableId,
            'wageable_type' => $wage->wageableType,
            'amount' => $wage->amount->amount,
            'currency' => $wage->amount->currency,
            'period' => $wage->period,
            'currency_id' => $wage->currencyId,
        ];
    }
}
