<?php
// modules/Currency/Presentation/Http/Presenter/CurrencyPresenter.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Presenter;

use Modules\Currency\Domain\Currency;

final class CurrencyPresenter
{
    public static function fromDomain(Currency $currency): array
    {
        return [
            'id' => $currency->uuid->value,
            'name' => $currency->name->getFor(),
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'is_active' => $currency->isActive,
        ];
    }
}
